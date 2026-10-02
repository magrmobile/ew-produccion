<?php

namespace Tests\Feature;

use App\Documents\DocumentBase;
use App\Http\Controllers\BillingController;
use App\Services\DteSchema;
use App\Services\InfileSimplifiedDteBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class DteSchemaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        foreach ([
            'DTE_ENVIRONMENT' => '00', 'DTE_ESTABLECIMIENTO' => 'M001', 'DTE_PUNTOVENTA' => 'P001',
            'DTE_EMISOR_NIT' => '06140411881014', 'DTE_EMISOR_NRC' => '123456',
            'DTE_EMISOR_NOMBRE' => 'Emisor de prueba', 'DTE_EMISOR_CODACTIVIDAD' => '27900',
            'DTE_EMISOR_DESCACTIVIDAD' => 'Fabricacion', 'DTE_EMISOR_NOMBRECOMERCIAL' => 'Emisor',
            'DTE_EMISOR_DIRECCION_DEPARTAMENTO' => '06', 'DTE_EMISOR_DIRECCION_MUNICIPIO' => '01',
            'DTE_EMISOR_DIRECCION_DISTRITO' => '01', 'DTE_EMISOR_DIRECCION_COMPLEMENTO' => 'Direccion de prueba',
            'DTE_EMISOR_TELEFONO' => '22223333', 'DTE_EMISOR_EMAIL' => 'emisor@example.com',
            'DTE_EMISOR_CODESTABLE' => 'M001', 'DTE_EMISOR_CODPUNTOVENTA' => 'P001',
            'DTE_EMISOR_TIPOITEMEXPOR' => '1',
            'DTE_EMISOR_TIPOESTABLECIMIENTO' => '02',
            'ONEWIRE_DTE_EMISOR_TIPOESTABLECIMIENTO' => '01',
        ] as $name => $value) {
            $this->originalEnvironment[$name] = getenv($name);
            putenv($name.'='.$value);
        }
        DocumentBase::consumeLocalCorrelatives(false);
        Auth::shouldReceive('user')->andReturn((object) ['name' => 'Usuario de prueba', 'numDocumento' => '12345678-9']);
        Auth::shouldReceive('check')->andReturn(true);
        DB::shouldReceive('table')->andReturnUsing(function ($table) {
            if ($table === 'documents') {
                throw new \RuntimeException('La prueba no debe consumir correlativos.');
            }
            $query = Mockery::mock();
            $query->shouldReceive('where')->andReturnUsing(function ($column, $value) use ($query, $table) {
                $this->catalogLookups[] = [$table, $column, $value];
                return $query;
            });
            $query->shouldReceive('value')->with('valor')->andReturn('Catalogo de prueba');
            $query->shouldReceive('first')->andReturn((object) [
                'id' => 1, 'valor' => 'Catalogo de prueba', 'codigo_mh' => 1,
                'codigo_plazo' => '01', 'periodo' => 1,
            ]);
            return $query;
        });
    }

    private $originalEnvironment = [];
    private $catalogLookups = [];

    protected function tearDown(): void
    {
        DocumentBase::consumeLocalCorrelatives(true);
        foreach ($this->originalEnvironment as $name => $value) {
            putenv($value === false ? $name : $name.'='.$value);
        }
        parent::tearDown();
    }

    private function document($type)
    {
        $customer = [
            'nit' => '06141405101010', 'nrc' => '123456', 'nombre' => 'Cliente de prueba',
            'codActividad' => '27900', 'descActividad' => 'Fabricacion', 'nombreComercial' => 'Cliente',
            'departamento' => '06', 'municipio' => '01', 'distrito' => '02',
            'complemento' => 'Direccion del cliente', 'telefono' => '22223333', 'correo' => 'cliente@example.com',
            'codPais' => 'GT', 'tipoPersona' => 2, 'bienTitulo' => '01',
            'category_id' => 2,
            'nombre_contacto' => 'Contacto de prueba', 'numdoc_contacto' => '12345678-9',
        ];
        $items = [[
            'unidad' => 1, 'cantidad' => 1, 'precio' => 100, 'monto' => 100,
            'descripcion' => 'Producto de prueba', 'item' => 'Producto de prueba',
            'numdoc' => '12345', 'date' => '01/10/2026',
        ]];
        $summary = ['monto' => 100, 'condicion' => 'Contado', 'codIncoterms' => '01'];
        $classes = [
            '01' => 'FacturaElectronica', '03' => 'ComprobanteCreditoFiscalElectronico',
            '04' => 'NotaRemisionElectronica', '05' => 'NotaCreditoElectronica',
            '06' => 'NotaDebitoElectronica', '07' => 'ComprobanteRetencionElectronico',
            '11' => 'FacturaExportacionElectronica', '14' => 'FacturaSujetoExcluidoElectronica',
        ];
        $class = 'App\\Documents\\'.$classes[$type];
        return (new $class($customer, $items, $summary, ['recintoFiscal' => '16', 'regimen' => 'EX-1.1000.000']))->toArray();
    }

    public function types()
    {
        return array_map(function ($type) { return [(string) $type]; }, array_keys(DteSchema::FILES));
    }

    /** @dataProvider types */
    public function test_generated_document_matches_current_schema($type)
    {
        $data = $this->document($type);
        $errors = DteSchema::errors(json_decode(json_encode($data)), DteSchema::schema($type));
        $this->assertSame([], $errors, implode("\n", $errors));
        $this->assertSame('01', $data['emisor']['direccion']['distrito']);
        if ($type !== '11') {
            $this->assertSame('02', $data['receptor']['direccion']['distrito']);
        }
        $this->assertSame($data, DteSchema::normalize($data));
    }

    public function test_missing_district_and_numeric_bounds_are_rejected()
    {
        $data = $this->document('03');
        unset($data['receptor']['direccion']['distrito']);
        $this->assertNotEmpty(DteSchema::errors(json_decode(json_encode($data)), DteSchema::schema('03')));
        $data = $this->document('03');
        $data['cuerpoDocumento'][0]['cantidad'] = 0;
        $this->assertNotEmpty(DteSchema::errors(json_decode(json_encode($data)), DteSchema::schema('03')));
        $data['cuerpoDocumento'][0]['cantidad'] = 100000000000;
        $this->assertNotEmpty(DteSchema::errors(json_decode(json_encode($data)), DteSchema::schema('03')));
    }

    /** @dataProvider types */
    public function test_pdf_renders_with_new_fields($type)
    {
        $templates = ['01' => 'fe', '03' => 'ccf', '04' => 'nr', '05' => 'nc', '06' => 'nd', '07' => 'cr', '11' => 'fexe', '14' => 'fse'];
        $data = json_decode(json_encode($this->document($type)));
        $this->assertFalse(isset($data->emisor->tipoEstablecimiento));
        $html = view('pdf.'.$templates[$type], [
            'data' => $data,
            'dir_emi' => ['desc_depto' => 'Departamento', 'desc_muni' => 'Municipio'],
            'dir_rec' => ['desc_depto' => 'Departamento', 'desc_muni' => 'Municipio'],
            'tipo_doc' => 'NIT', 'modelo_fact' => 'Modelo', 'tipo_trans' => 'Normal',
            'tipo_establec' => $this->establishmentDescription($data, 'DTE_EMISOR'), 'cond_opera' => 'Contado',
            'rec_fiscal' => 'Recinto', 'regimen' => 'Regimen',
            'nombre_contacto' => 'Contacto', 'numdoc_contacto' => '12345678-9',
        ])->render();
        $this->assertStringContainsString('Cliente de prueba', $html);
    }

    private function establishmentDescription($data, $prefix)
    {
        $method = new \ReflectionMethod(BillingController::class, 'getIssuerEstablishmentDescription');
        $method->setAccessible(true);
        return $method->invoke(new BillingController(), $data, ['env_prefix' => $prefix]);
    }

    public function test_pdf_establishment_uses_selected_issuer_configuration()
    {
        $data = json_decode(json_encode($this->document('03')));
        $this->assertSame('Catalogo de prueba', $this->establishmentDescription($data, 'DTE_EMISOR'));
        $this->assertContains(['cat009', 'id', '02'], $this->catalogLookups);
        $this->assertSame('Catalogo de prueba', $this->establishmentDescription($data, 'ONEWIRE_DTE_EMISOR'));
        $this->assertContains(['cat009', 'id', '01'], $this->catalogLookups);
        putenv('DTE_EMISOR_TIPOESTABLECIMIENTO=');
        $this->assertSame('', $this->establishmentDescription($data, 'DTE_EMISOR'));
    }

    public function test_infile_uses_new_iva_and_discount_fields()
    {
        $data = $this->document('03');
        $this->assertSame(1.0, (float) $data['resumen']['ivaPerci']);
        $payload = (new InfileSimplifiedDteBuilder())->build($data)['documento'];
        $this->assertFalse($payload['percibir_iva']);
        $this->assertEquals(113, $payload['pagos'][0]['monto']);
        $this->assertSame('02', $payload['receptor']['direccion']['distrito']);
        $export = $this->document('11');
        $export['resumen']['descuGravada'] = 10;
        $payload = (new InfileSimplifiedDteBuilder())->build($export)['documento'];
        $this->assertEquals(10, $payload['descuento_global']);
    }

    public function test_invalidation_matches_v3_and_preserves_request_codes()
    {
        $source = $this->document('05');
        $source['selloRecibido'] = str_repeat('A', 40);
        $input = ['codigoGeneracion' => $source['identificacion']['codigoGeneracion'],
            'codigoGeneracionR' => null, 'tipoAnulacion' => 2, 'motivoAnulacion' => 'Prueba'];
        $data = DteSchema::invalidation($source, $input);
        $schema = json_decode(file_get_contents(base_path('resources/fe_schemas/v3/invalidacion-schema-v3.json')));
        $errors = DteSchema::errors(json_decode(json_encode($data)), $schema);
        $this->assertSame([], $errors, implode("\n", $errors));
        $this->assertNotSame($input['codigoGeneracion'], $data['identificacion']['codigoGeneracion']);
        $this->assertSame($input['codigoGeneracion'], $data['documento']['codigoGeneracion']);
        $this->assertNull($data['documento']['codigoGeneracionR']);
        $input['codigoGeneracionR'] = '623FC453-B37B-42BC-8072-890DF6CB440C';
        $this->assertSame($input['codigoGeneracionR'], DteSchema::invalidation($source, $input)['documento']['codigoGeneracionR']);
    }
}
