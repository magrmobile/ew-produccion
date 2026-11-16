<?php

namespace Tests\Feature;

use App\dte;
use App\User;
use App\Http\Controllers\dteController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class InfileInvalidationTest extends TestCase
{
    private $history = [];
    private $controller;
    private $queue;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('dtes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('codigoGeneracion');
            $table->string('numeroControl');
            $table->text('json_dte');
            $table->string('provider')->nullable();
            $table->string('emisor_nit')->nullable();
            $table->boolean('received')->default(true);
            $table->boolean('signed')->default(false);
            $table->text('sign')->nullable();
            $table->integer('signed_by')->nullable();
            $table->dateTime('signed_date')->nullable();
            $table->integer('received_by')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->text('stamp')->nullable();
            $table->boolean('invalidate')->default(false);
            $table->text('invalidate_stamp')->nullable();
            $table->dateTime('invalidate_date')->nullable();
            $table->integer('invalidate_by')->nullable();
            $table->timestamps();
        });
        config([
            'services.infile.environment' => 'test',
            'services.infile.invalidate_url' => null,
            'services.infile.api_key' => 'test-key-not-a-real-secret',
            'services.infile.invalidation_response_confirmed' => false,
        ]);
        Storage::fake('local');
        $this->queue = new MockHandler();
        $handler = HandlerStack::create($this->queue);
        $handler->push(Middleware::history($this->history));
        $this->controller = new InvalidationTestController();
        $this->controller->client = new Client(['handler' => $handler]);
        $this->app->instance(dteController::class, $this->controller);
        Route::middleware('web')->post('/_test/invalidate/{id}', function ($id, \Illuminate\Http\Request $request) {
            return app(dteController::class)->invalidateDte(dte::findOrFail($id), $request);
        });
        Route::middleware('web')->post('/_test/certify/{id}', function ($id) {
            return app(dteController::class)->sendDte($id);
        });
        $user = new User();
        $user->id = 7;
        $this->be($user);
    }

    private function document(array $attributes = [])
    {
        $code = '623FC453-B37B-42BC-8072-890DF6CB440C';
        $nit = '05011011221017';
        $source = [
            'identificacion' => ['codigoGeneracion' => $code, 'numeroControl' => 'DTE-03-M001P001-000000000000009',
                'ambiente' => '00', 'tipoDte' => '03', 'fecEmi' => '2026-11-13', 'horEmi' => '10:20:30'],
            'emisor' => ['nit' => $nit, 'nombre' => 'Emisor de prueba', 'codEstable' => 'M001',
                'codEstableMH' => 'M001', 'codPuntoVenta' => 'P001', 'codPuntoVentaMH' => 'P001',
                'telefono' => '22223333', 'correo' => 'emisor@example.com'],
            'receptor' => ['nit' => '06141405101010', 'nombre' => 'Cliente de prueba',
                'telefono' => '22224444', 'correo' => 'cliente@example.com'],
            'selloRecibido' => str_repeat('A', 40),
        ];
        return dte::create(array_merge([
            'customer_id' => 1, 'provider' => 'infile', 'emisor_nit' => $nit,
            'codigoGeneracion' => $code, 'numeroControl' => $source['identificacion']['numeroControl'],
            'json_dte' => json_encode($source), 'received' => 1, 'invalidate' => 0,
        ], $attributes));
    }

    private function submit(dte $dte, array $input = [])
    {
        return $this->from('/invalidate/'.$dte->id)->post('/_test/invalidate/'.$dte->id, array_merge([
            'codigoGeneracion' => $dte->codigoGeneracion, 'tipoAnulacion' => 2, 'motivoAnulacion' => 'Prueba controlada',
        ], $input));
    }

    private function respond(array $body, $status = 200)
    {
        $this->queue->append(new Response($status, ['Content-Type' => 'application/json'], json_encode($body)));
    }

    // Anonymized structure of the actual Sandbox response supplied on 2026-11-14.
    private function successResponse()
    {
        return json_decode(file_get_contents(__DIR__.'/../Fixtures/infile-invalidation-success.json'), true);
    }

    public function test_infile_request_uses_documented_endpoint_headers_and_payload_without_rrd()
    {
        $dte = $this->document();
        $this->respond($this->successResponse());
        $this->submit($dte)->assertRedirect('/invalidate/'.$dte->id)->assertSessionHas('errors');
        $this->assertCount(1, $this->history);
        $request = $this->history[0]['request'];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://certificador.infile.com.sv/api/v1/certificacion/test/documento/invalidacion', (string) $request->getUri());
        $this->assertSame($dte->emisor_nit, $request->getHeaderLine('usuario'));
        $this->assertSame('test-key-not-a-real-secret', $request->getHeaderLine('llave'));
        $this->assertFalse($request->hasHeader('Authorization'));
        $payload = json_decode((string) $request->getBody(), true);
        $this->assertSame(['invalidacion'], array_keys($payload));
        $event = $payload['invalidacion'];
        $this->assertSame($dte->codigoGeneracion, $event['uuid']);
        $this->assertSame('M001', $event['establecimiento']);
        $this->assertSame(2, $event['tipo_anulacion']);
        $this->assertSame('Prueba controlada', $event['motivo']);
        $this->assertSame('05011011221017', $event['responsable']['numero_documento']);
        $this->assertSame('36', $event['solicitante']['tipo_documento']);
        $this->assertSame('cliente@example.com', $event['solicitante']['correo']);
        foreach (['nuevo_documento', 'fecha_evento', 'hora_evento', 'documento'] as $key) {
            $this->assertArrayNotHasKey($key, $event);
        }
        $this->assertSame(0, $this->controller->authCalls);
        $this->assertEquals(0, $dte->fresh()->invalidate);
        $files = Storage::disk('local')->allFiles('infile/invalidations/'.$dte->id);
        $this->assertCount(1, $files);
        $this->assertStringNotContainsString('test-key-not-a-real-secret', Storage::disk('local')->get($files[0]));
    }

    public function replacementTypes()
    {
        return [[1], [3]];
    }

    /** @dataProvider replacementTypes */
    public function test_replacement_is_required_and_valid_replacement_is_sent($type)
    {
        $dte = $this->document();
        $this->submit($dte, ['tipoAnulacion' => $type])->assertSessionHasErrors('codigoGeneracionR');
        $replacement = $this->document(['codigoGeneracion' => 'A23FC453-B37B-42BC-8072-890DF6CB440C']);
        $this->respond(['ok' => false, 'mensaje' => 'Rechazado']);
        $this->submit($dte, ['tipoAnulacion' => $type, 'codigoGeneracionR' => $replacement->codigoGeneracion]);
        $this->assertSame($replacement->codigoGeneracion, json_decode((string) $this->history[0]['request']->getBody(), true)['invalidacion']['nuevo_documento']);
        $this->assertSame(0, $this->controller->authCalls);
    }

    public function invalidReplacements()
    {
        return [[['customer_id' => 2]], [['provider' => 'rrd']], [['emisor_nit' => '06140411881014']],
            [['received' => 0]], [['invalidate' => 1]]];
    }

    /** @dataProvider invalidReplacements */
    public function test_invalid_replacement_does_not_send_a_request($attributes)
    {
        $dte = $this->document();
        $replacement = $this->document(array_merge(['codigoGeneracion' => 'A23FC453-B37B-42BC-8072-890DF6CB440C'], $attributes));
        $this->submit($dte, ['tipoAnulacion' => 1, 'codigoGeneracionR' => $replacement->codigoGeneracion])->assertSessionHasErrors('codigoGeneracionR');
        $this->assertCount(0, $this->history);
    }

    public function test_uuid_tampering_and_using_same_document_as_replacement_are_rejected()
    {
        $dte = $this->document();
        $this->submit($dte, ['codigoGeneracion' => 'A23FC453-B37B-42BC-8072-890DF6CB440C'])->assertSessionHasErrors('codigoGeneracion');
        $this->submit($dte, ['tipoAnulacion' => 3, 'codigoGeneracionR' => $dte->codigoGeneracion])->assertSessionHasErrors('codigoGeneracionR');
        $this->assertCount(0, $this->history);
    }

    public function test_uncertified_and_already_invalidated_documents_are_blocked()
    {
        $this->submit($this->document(['received' => 0]))->assertSessionHas('errors');
        $this->submit($this->document(['invalidate' => 1]))->assertSessionHas('errors');
        $this->assertCount(0, $this->history);
    }

    public function test_unknown_provider_or_inconsistent_certified_identity_is_blocked()
    {
        $this->submit($this->document(['provider' => 'unknown']))->assertSessionHas('errors');
        $dte = $this->document(['emisor_nit' => '06140411881014']);
        $this->submit($dte)->assertSessionHas('errors');
        $dte = $this->document();
        $data = json_decode($dte->json_dte, true);
        $data['identificacion']['codigoGeneracion'] = 'A23FC453-B37B-42BC-8072-890DF6CB440C';
        $dte->update(['json_dte' => json_encode($data)]);
        $this->submit($dte)->assertSessionHas('errors');
        $this->assertCount(0, $this->history);
        $this->assertSame(0, $this->controller->authCalls);
    }

    public function test_production_is_blocked_until_response_contract_is_confirmed()
    {
        config(['services.infile.environment' => 'prod']);
        $dte = $this->document();
        $data = json_decode($dte->json_dte, true);
        $data['identificacion']['ambiente'] = '01';
        $dte->update(['json_dte' => json_encode($data)]);
        $this->submit($dte)->assertSessionHas('errors');
        $this->assertCount(0, $this->history);
    }

    public function test_only_confirmed_complete_success_updates_the_record()
    {
        config(['services.infile.invalidation_response_confirmed' => true]);
        $dte = $this->document();
        $originalJson = $dte->json_dte;
        $this->respond($this->successResponse());
        $this->submit($dte)->assertSessionHas('message');
        $dte = $dte->fresh();
        $this->assertEquals(1, $dte->invalidate);
        $this->assertSame('202689015DABD4E84A62B3A6DBE79B024A61GIKF', $dte->invalidate_stamp);
        $this->assertSame('2026-11-14 08:03:00', $dte->invalidate_date);
        $this->assertEquals(7, $dte->invalidate_by);
        $this->assertSame('623FC453-B37B-42BC-8072-890DF6CB440C', $dte->codigoGeneracion);
        $this->assertSame($originalJson, $dte->json_dte);
        $this->assertSame('DTE-03-M001P001-000000000000009', $dte->numeroControl);
    }

    public function inconsistentEvents()
    {
        return [
            ['json.documento.codigoGeneracion', 'A23FC453-B37B-42BC-8072-890DF6CB440C'],
            ['json.emisor.nit', '06140411881014'],
            ['json.identificacion.ambiente', '01'],
            ['respuesta_dgi.ambiente', '01'],
            ['respuesta.codigoGeneracion', 'A23FC453-B37B-42BC-8072-890DF6CB440C'],
            ['respuesta_dgi.codigoGeneracion', 'A23FC453-B37B-42BC-8072-890DF6CB440C'],
            ['json.identificacion.version', 2],
            ['respuesta_dgi.version', 2],
            ['respuesta.selloRecepcion', 'OTRO-SELLO'],
            ['respuesta_dgi.selloRecibido', ''],
            ['respuesta_dgi.fhProcesamiento', '31/02/2026 10:30:00'],
            ['json.documento', null],
        ];
    }

    /** @dataProvider inconsistentEvents */
    public function test_event_must_match_the_original_document_and_have_consistent_confirmation($path, $value)
    {
        config(['services.infile.invalidation_response_confirmed' => true]);
        $dte = $this->document();
        $response = $this->successResponse();
        data_set($response, $path, $value);
        $this->respond($response);
        $this->submit($dte)->assertSessionHas('errors');
        $this->assertEquals(0, $dte->fresh()->invalidate);
        $this->assertNull($dte->fresh()->invalidate_stamp);
    }

    public function test_original_migration_actor_column_is_supported_without_migration()
    {
        // Recreate only the isolated SQLite table with the original migration's name.
        Schema::drop('dtes');
        Schema::create('dtes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('codigoGeneracion');
            $table->string('numeroControl');
            $table->text('json_dte');
            $table->string('provider');
            $table->string('emisor_nit');
            $table->boolean('received');
            $table->boolean('invalidate');
            $table->text('invalidate_stamp')->nullable();
            $table->dateTime('invalidate_date')->nullable();
            $table->integer('invalidated_by')->nullable();
            $table->timestamps();
        });
        config(['services.infile.invalidation_response_confirmed' => true]);
        $dte = $this->document();
        $this->respond($this->successResponse());
        $this->submit($dte)->assertSessionHas('message');
        $this->assertEquals(7, $dte->fresh()->invalidated_by);
        $this->assertSame('invalidated_by', $dte->fresh()->invalidate_user()->getForeignKey());
    }

    public function test_production_and_explicit_url_use_matching_environment()
    {
        $dte = $this->document();
        $data = json_decode($dte->json_dte, true);
        $data['identificacion']['ambiente'] = '01';
        $dte->update(['json_dte' => json_encode($data)]);
        config(['services.infile.environment' => 'prod', 'services.infile.invalidation_response_confirmed' => true,
            'services.infile.invalidate_url' => 'https://certificador.infile.com.sv/api/v1/certificacion/prod/documento/invalidacion']);
        $this->respond(['ok' => false, 'mensaje' => 'Rechazado']);
        $this->submit($dte)->assertSessionHas('errors');
        $this->assertSame(config('services.infile.invalidate_url'), (string) $this->history[0]['request']->getUri());
        $this->assertEquals(0, $dte->fresh()->invalidate);
    }

    public function test_missing_establishment_uses_matching_issuer_configuration()
    {
        $dte = $this->document();
        $data = json_decode($dte->json_dte, true);
        unset($data['emisor']['codEstable']);
        $dte->update(['json_dte' => json_encode($data)]);
        $nit = getenv('ONEWIRE_DTE_EMISOR_NIT');
        $establishment = getenv('ONEWIRE_DTE_EMISOR_CODESTABLE');
        try {
            putenv('ONEWIRE_DTE_EMISOR_NIT=05011011221017');
            putenv('ONEWIRE_DTE_EMISOR_CODESTABLE=M002');
            $this->respond(['ok' => false]);
            $this->submit($dte);
            $this->assertSame('M002', json_decode((string) $this->history[0]['request']->getBody(), true)['invalidacion']['establecimiento']);
        } finally {
            putenv($nit === false ? 'ONEWIRE_DTE_EMISOR_NIT' : 'ONEWIRE_DTE_EMISOR_NIT='.$nit);
            putenv($establishment === false ? 'ONEWIRE_DTE_EMISOR_CODESTABLE' : 'ONEWIRE_DTE_EMISOR_CODESTABLE='.$establishment);
        }
    }

    public function nonSuccessResponses()
    {
        return [
            [['ok' => true]],
            [['ok' => false, 'mensaje' => 'Rechazado', 'errores' => ['motivo' => 'Motivo no valido']]],
            [['ok' => true, 'respuesta_dgi' => ['estado' => 'RECHAZADO']]],
            [['ok' => true, 'respuesta_dgi' => ['estado' => 'PROCESADO', 'codigoMsg' => '001']]],
            [['ok' => true, 'respuesta_dgi' => ['estado' => 'PROCESADO', 'codigoMsg' => '001', 'selloRecibido' => 'SELLO', 'fhProcesamiento' => '31/02/2026 10:30:00']]],
        ];
    }

    /** @dataProvider nonSuccessResponses */
    public function test_http_200_without_verified_success_never_updates_document($body)
    {
        config(['services.infile.invalidation_response_confirmed' => true]);
        $dte = $this->document();
        $this->respond($body);
        $this->submit($dte)->assertSessionHas('errors');
        $this->assertEquals(0, $dte->fresh()->invalidate);
        $this->assertNull($dte->fresh()->invalidate_stamp);
    }

    public function test_http_failure_non_json_and_timeout_do_not_update_document()
    {
        $dte = $this->document();
        $this->respond(['ok' => false, 'mensaje' => 'No autorizado'], 401);
        $this->submit($dte)->assertSessionHas('errors');
        $this->queue->append(new Response(502, [], '<html>Error</html>'));
        $this->submit($dte)->assertSessionHas('errors');
        $this->queue->append(new ConnectException('timeout', new Request('POST', 'https://certificador.infile.com.sv')));
        $this->submit($dte)->assertSessionHas('errors');
        $this->assertEquals(0, $dte->fresh()->invalidate);
        $this->assertSame(0, $this->controller->authCalls);
    }

    public function test_bad_environment_url_key_and_missing_email_are_blocked()
    {
        $dte = $this->document();
        config(['services.infile.environment' => 'other']);
        $this->submit($dte)->assertSessionHas('errors');
        config(['services.infile.environment' => 'prod']);
        $this->submit($dte)->assertSessionHas('errors');
        config(['services.infile.environment' => 'test', 'services.infile.invalidate_url' => 'https://api-felsv.svrrdca.com/felsv/anulacion/']);
        $this->submit($dte)->assertSessionHas('errors');
        config(['services.infile.invalidate_url' => null, 'services.infile.api_key' => '']);
        $this->submit($dte)->assertSessionHas('errors');
        config(['services.infile.api_key' => 'test-key']);
        $data = json_decode($dte->json_dte, true);
        unset($data['receptor']['correo']);
        $dte->update(['json_dte' => json_encode($data)]);
        $this->submit($dte)->assertSessionHasErrors('solicitante.correo');
        $this->assertCount(0, $this->history);
    }

    public function test_rrd_uses_original_signature_and_endpoint_with_lazy_authentication()
    {
        $dte = $this->document(['provider' => 'rrd']);
        $this->assertSame(0, $this->controller->authCalls);
        $this->respond(['status' => 'OK', 'body' => 'signed-event']);
        $this->respond(['estado' => 'PROCESADO', 'selloRecibido' => 'RRD-STAMP', 'fhProcesamiento' => '13/11/2026 10:30:00']);
        $this->submit($dte)->assertSessionHas('message');
        $this->assertCount(2, $this->history);
        $this->assertSame(env('API_SIGN_URL'), (string) $this->history[0]['request']->getUri());
        $this->assertSame(env('API_INVALIDATE_URL'), (string) $this->history[1]['request']->getUri());
        $this->assertSame('Bearer rrd-test-token', $this->history[1]['request']->getHeaderLine('Authorization'));
        $this->assertSame('signed-event', json_decode((string) $this->history[1]['request']->getBody(), true)['documento']);
        $this->assertSame(1, $this->controller->authCalls);
        $this->assertSame('RRD-STAMP', $dte->fresh()->invalidate_stamp);
    }

    public function test_https_verification_uses_bundled_ca_and_rejects_unreadable_override()
    {
        $dte = $this->document();
        config(['services.infile.ca_bundle' => null]);
        $this->respond(['ok' => false]);
        $this->submit($dte);
        $bundle = $this->history[0]['options']['verify'];
        $this->assertSame(base_path('resources/certificates/cacert.pem'), $bundle);
        $this->assertTrue(is_readable($bundle));
        $this->assertNotFalse(openssl_x509_read(file_get_contents($bundle)));
        config(['services.infile.ca_bundle' => base_path('missing-ca-bundle.pem')]);
        $this->submit($dte)->assertSessionHasErrors();
        $this->assertCount(1, $this->history);
    }

    public function test_tls_failure_has_a_safe_diagnostic_without_exposing_the_key()
    {
        $dte = $this->document();
        Log::shouldReceive('warning')->once()->with('Fallo en invalidacion Infile', \Mockery::on(function ($context) use ($dte) {
            return $context['stage'] === 'envio' && $context['curl_errno'] === 60
                && $context['dte_id'] === $dte->id && $context['receipt'] === null
                && strpos(json_encode($context), 'test-key-not-a-real-secret') === false;
        }));
        $this->queue->append(new ConnectException('SSL failed: test-key-not-a-real-secret', new Request('POST', 'https://certificador.infile.com.sv'), null, ['errno' => 60]));
        $this->submit($dte)->assertSessionHasErrors();
        $messages = session('errors')->all();
        $this->assertStringContainsString('certificado HTTPS', implode(' ', $messages));
        $this->assertStringContainsString('Referencia de diagnostico', implode(' ', $messages));
        $this->assertStringNotContainsString('test-key-not-a-real-secret', implode(' ', $messages));
        $this->assertEquals(0, $dte->fresh()->invalidate);
    }

    public function test_database_failure_after_success_reports_saved_response_and_keeps_record_unchanged()
    {
        config(['services.infile.invalidation_response_confirmed' => true]);
        $dte = $this->document();
        dte::updating(function () {
            throw new \RuntimeException('database failure with private bindings');
        });
        Log::shouldReceive('warning')->once()->with('Fallo en invalidacion Infile', \Mockery::on(function ($context) {
            return $context['stage'] === 'actualizar_registro' && $context['http_status'] === 200 && $context['receipt'] !== null;
        }));
        $this->respond($this->successResponse());
        $this->submit($dte)->assertSessionHasErrors();
        $messages = implode(' ', session('errors')->all());
        $this->assertStringContainsString('fallo la actualizacion', $messages);
        $this->assertStringContainsString('Referencia de respuesta', $messages);
        $this->assertStringNotContainsString('private bindings', $messages);
        $this->assertEquals(0, $dte->fresh()->invalidate);
        $this->assertCount(1, Storage::disk('local')->allFiles('infile/invalidations/'.$dte->id));
    }

    public function test_storage_failure_is_distinguished_from_connection_failure()
    {
        $dte = $this->document();
        $disk = \Mockery::mock();
        $disk->shouldReceive('put')->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('local')->once()->andReturn($disk);
        Log::shouldReceive('warning')->once()->with('Fallo en invalidacion Infile', \Mockery::on(function ($context) {
            return $context['stage'] === 'guardar_respuesta' && $context['http_status'] === 200 && $context['receipt'] === null;
        }));
        $this->respond($this->successResponse());
        $this->submit($dte)->assertSessionHasErrors();
        $this->assertStringContainsString('almacenamiento local', implode(' ', session('errors')->all()));
        $this->assertEquals(0, $dte->fresh()->invalidate);
    }

    public function certificationDates()
    {
        return [
            ['14/11/2026 08:03:00', null, '2026-11-14 08:03:00'],
            ['2026-11-14 08:03:00', null, '2026-11-14 08:03:00'],
            ['2026-11-14T08:03:00', null, '2026-11-14 08:03:00'],
            ['2026-11-14T14:03:00.000Z', null, '2026-11-14 08:03:00'],
            ['2026-11-14T08:03:00.123-06:00', null, '2026-11-14 08:03:00'],
            [null, '2026-11-14 08:03:00', '2026-11-14 08:03:00'],
            ['unrecognized', '2026-11-14 08:03:00', '2026-11-14 08:03:00'],
            ['unrecognized', null, null],
        ];
    }

    /** @dataProvider certificationDates */
    public function test_fexe_certification_is_saved_with_sandbox_date_variants($processingDate, $emissionDate, $expected)
    {
        $environment = [
            'INFILE_ENVIRONMENT' => 'test',
            'INFILE_CERTIFY_URL' => 'https://certificador.infile.com.sv/api/v1/certificacion/test/documento/certificar',
            'INFILE_API_KEY' => 'test-key-not-a-real-secret',
        ];
        $original = [];
        foreach ($environment as $name => $value) {
            $original[$name] = getenv($name);
            putenv($name.'='.$value);
        }
        try {
            $dte = $this->document(['received' => 0]);
            $data = json_decode($dte->json_dte, true);
            $data['identificacion']['tipoDte'] = '11';
            $data['resumen'] = ['totalPagar' => 100, 'ivaPerci' => 0, 'pagos' => [['codigo' => '01', 'montoPago' => 100]]];
            $data['cuerpoDocumento'] = [['numItem' => 1, 'cantidad' => 1, 'precioUni' => 100, 'ventaGravada' => 100, 'montoDescu' => 0, 'noGravado' => 0]];
            $dte->update(['json_dte' => json_encode($data)]);
            $certifiedCode = 'A23FC453-B37B-42BC-8072-890DF6CB440C';
            $control = 'DTE-11-M001P001-000000000000010';
            $data['identificacion']['codigoGeneracion'] = $certifiedCode;
            $data['identificacion']['numeroControl'] = $control;
            $this->respond([
                'ok' => true,
                'respuesta' => ['codigoGeneracion' => $certifiedCode, 'numeroControl' => $control, 'fechaEmision' => $emissionDate],
                'respuesta_dgi' => ['estado' => 'PROCESADO', 'selloRecibido' => 'CERTIFIED-STAMP', 'fhProcesamiento' => $processingDate],
                'json' => $data,
            ]);
            $this->from('/billing')->post('/_test/certify/'.$dte->id)->assertSessionHas('message');
            $saved = $dte->fresh();
            $this->assertEquals(1, $saved->received);
            $this->assertSame('CERTIFIED-STAMP', $saved->stamp);
            $this->assertSame($certifiedCode, $saved->codigoGeneracion);
            $this->assertSame($control, $saved->numeroControl);
            $this->assertNotEmpty($saved->received_date);
            if ($expected !== null) {
                $this->assertSame($expected, $saved->received_date);
            }
            $this->assertCount(1, $this->history);
            $this->assertSame(0, $this->controller->authCalls);
        } finally {
            foreach ($original as $name => $value) {
                putenv($value === false ? $name : $name.'='.$value);
            }
        }
    }
}

class InvalidationTestController extends dteController
{
    public $client;
    public $authCalls = 0;

    protected function httpClient(array $options = [])
    {
        return $this->client;
    }

    public function obtenerTokenJWT()
    {
        $this->authCalls++;
        return 'rrd-test-token';
    }
}
