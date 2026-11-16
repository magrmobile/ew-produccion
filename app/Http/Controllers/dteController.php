<?php

namespace App\Http\Controllers;

use App\Customer;
use App\dte;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\InfileSimplifiedDteBuilder;
use App\Services\InfileInvalidation;
use App\Services\InfileDateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpKernel\Event\ViewEvent;

use App\Services\DteSchema;

class dteController extends Controller
{
    protected $token;

    protected function httpClient(array $options = [])
    {
        return new Client($options);
    }

    protected function rrdToken()
    {
        if (!$this->token) {
            $token = $this->obtenerTokenJWT();
            if (!is_string($token) || $token === '') {
                throw new \RuntimeException('No se pudo obtener el token de RRD.');
            }
            $this->token = $token;
        }
        return $this->token;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer_id = $request->customer_id;
        $customer = Customer::where('id', $customer_id)->first();
        $dtes = dte::where('customer_id', $customer_id)->get();
        return view('dtes.index', compact('dtes', 'customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(dte $dte)
    {
        return view('dtes.show', compact('dte'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendDte($id) 
    {
        $dte = dte::find($id);

        if ($dte->provider === 'infile') {
            return $this->sendInfileDte($dte);
        }

        if($dte->signed == 0) {
            $signed_dte = $this->signDte($id);
        } else {
            $signed_dte = $dte;
        }

        //dd($signed_dte->json_dte);

        $dteJson = json_decode($signed_dte->json_dte);
        $client = $this->httpClient(['verify' => false]);

        $url = env('API_RECEIVED_URL');

        $headers = [
            'User-Agent' => '',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->rrdToken(),
        ];

        $params = [
            'ambiente' => env('DTE_ENVIRONMENT'),
            'idEnvio' => 1,
            'version' => $dteJson->identificacion->version,
            'tipoDte' => $dteJson->identificacion->tipoDte,
            'nitEmisor' => env('DTE_EMISOR_NIT'),
            'codGen' => $dteJson->identificacion->codigoGeneracion,
            'documento' => $signed_dte->sign
        ];

        try {
            $response = $client->post($url, ['headers' => $headers, 'json' => $params]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() === 200) {
                //dd($responseData);
                switch($responseData['estado']) {
                    case 'PROCESADO':
                        $dteJson->selloRecibido = $responseData['selloRecibido'];

                        $fechaHora =  $responseData['fhProcesamiento'];
                        $fechaHoraFormateada = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $fechaHora)->format('Y-m-d H:i:s');

                        $signed_dte->update([
                            'json_dte' => json_encode($dteJson),
                            'received' => 1,
                            'stamp' => $responseData['selloRecibido'],
                            'received_by' => auth()->user()->id,
                            'received_date' => $fechaHoraFormateada
                        ]);

                        //Alert::success('Transmision Realizada', 'El Documento fue enviado Satisfactoriamente');
                        return back()->with('message', 'Documento enviado satisfactoriamente!!');
                    break;
                    case 'RECHAZADO':
                        $errors[] = 'RECHAZADO: CODIGO ('.$responseData['codigoMsg'].') - '.$responseData['descripcionMsg'];
                        $observaciones = $responseData['observaciones'];

                        foreach($observaciones as $observacion) {
                            $errors[] = $observacion;
                        }

                        return back()->with('errors', $errors);
                    break;
                    default:
                        $errors = $responseData['observaciones'];
                        return back()->with('errors', $errors);
                    break;
                }
            }
        } catch(\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            $errors[] = $e->getMessage();
            return back()->with('errors', $errors);
        }
        //return response()->json($params);
    } 

    private function sendInfileDte(dte $dte)
    {
        $client = $this->httpClient(['verify' => false]);
        $dteJson = json_decode($dte->json_dte);
        $payload = (new InfileSimplifiedDteBuilder())->build($dteJson);

        //dd(json_encode($payload));

        $url = env('INFILE_CERTIFY_URL');

        if (!$url) {
            $environment = env('INFILE_ENVIRONMENT', 'test');
            $url = 'https://certificador.infile.com.sv/api/v1/certificacion/'.$environment.'/documento/certificar';
        }

        $issuerNit = $dte->emisor_nit ?: env('DTE_EMISOR_NIT');

        if (!env('INFILE_API_KEY')) {
            return back()->with('errors', ['No se ha configurado INFILE_API_KEY en el archivo .env.']);
        }

        $headers = [
            'Content-Type' => 'application/json',
            'usuario' => $issuerNit,
            'llave' => env('INFILE_API_KEY'),
            'identificador' => $dte->codigoGeneracion,
            'origen' => env('INFILE_ORIGIN', config('app.name')),
        ];

        try {
            $response = $client->post($url, ['headers' => $headers, 'json' => $payload]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            $statusCode = $response->getStatusCode();
            $isSuccessfulResponse = $statusCode >= 200 && $statusCode < 300;

            if($isSuccessfulResponse && data_get($responseData, 'ok') === true) {
                $certifiedJson = data_get($responseData, 'json', $dte->json_dte);
                $certifiedData = is_string($certifiedJson) ? json_decode($certifiedJson, true) : $certifiedJson;
                $codigoGeneracion = data_get($responseData, 'respuesta.codigoGeneracion', data_get($certifiedData, 'identificacion.codigoGeneracion', $dte->codigoGeneracion));
                $numeroControl = data_get($responseData, 'respuesta.numeroControl', data_get($certifiedData, 'identificacion.numeroControl', $dte->numeroControl));
                $sello = data_get($responseData, 'respuesta_dgi.selloRecibido', data_get($responseData, 'respuesta.selloRecepcion'));
                $receivedDate = InfileDateTime::databaseValue(data_get($responseData, 'respuesta_dgi.fhProcesamiento'))
                    ?: InfileDateTime::databaseValue(data_get($responseData, 'respuesta.fechaEmision'));
                if (!$receivedDate) {
                    Log::warning('Fecha de certificacion Infile no reconocida; se utiliza la fecha local de recepcion.', [
                        'dte_id' => $dte->id,
                    ]);
                }

                $dte->update([
                    'json_dte' => is_string($certifiedJson) ? $certifiedJson : json_encode($certifiedJson),
                    'codigoGeneracion' => $codigoGeneracion,
                    'numeroControl' => $numeroControl,
                    'signed' => 1,
                    'sign' => data_get($certifiedData, 'firmaElectronica'),
                    'signed_by' => auth()->user()->id,
                    'signed_date' => DB::raw('CURRENT_TIMESTAMP'),
                    'received' => 1,
                    'stamp' => $sello,
                    'received_by' => auth()->user()->id,
                    'received_date' => $receivedDate ?: DB::raw('CURRENT_TIMESTAMP')
                ]);

                return back()->with('message', 'Documento enviado satisfactoriamente a Infile!!');
            }

            $errors = [];
            $errors[] = data_get($responseData, 'mensaje', 'Infile rechazo el documento.');

            foreach ((array) data_get($responseData, 'errores', []) as $error) {
                $errors[] = is_string($error) ? $error : json_encode($error);
            }

            return back()->with('errors', $errors);
        } catch(\Exception $e) {
            $errors[] = $e->getMessage();
            return back()->with('errors', $errors);
        }
    }

    public function signDte($id)
    {
        $dte = dte::find($id);

        //dd($this->token);

        $client = $this->httpClient(['verify' => false]);

        $dteJson = json_decode($dte->json_dte);

        $url = env('API_SIGN_URL');
        
        $nit = env('API_SIGN_NIT');
        $passwordPri = env('API_SIGN_PASSWORD');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->rrdToken(),
        ];

        $params = [
            'activo' => true,
            'nit' => $nit,
            'passwordPri' => $passwordPri,
            'dteJson' => $dteJson
        ];

        //dd($params);

        try {
            $response = $client->post($url, ['headers' => $headers, 'json' => $params]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() === 200) {
                if(isset($responseData['status']) && $responseData['status'] === 'OK') {
                    $dteJson->firmaElectronica = $responseData['body'];

                    $dte->update([
                        'json_dte' => json_encode($dteJson),
                        'signed' => 1,
                        'sign' => $responseData['body'],
                        'signed_by' => auth()->user()->id,
                        'signed_date' => DB::raw('CURRENT_TIMESTAMP')
                    ]);

                    return $dte;
                } else {
                    //return response()->json('error', 'Error en la respuesta de la API: '.$responseData['message'], 500);
                    $errors[] = 'Error en la respuesta de la API: '.$responseData['message'];
                    return back()->with('errors', $errors);
                }
            }
        } catch(\Exception $e) {
            //dd($e);
            //return response()->json(['error' => $e->getMessage()], 500);
            $errors[] = $e->getMessage();
            return back()->with('errors', $errors);
        }
    }

    public function showInvalidate(dte $dte){
        $tiposAnulacion = DB::table('cat024')->get();
        return view('dtes.invalidate', compact('dte', 'tiposAnulacion'));
    }

    public function invalidateDte(dte $dte, Request $request) {
        if ((int) $dte->received !== 1 || (int) $dte->invalidate === 1) {
            return back()->withErrors(['Solo se pueden invalidar documentos certificados que no esten invalidados.']);
        }
        $request->validate([
            'tipoAnulacion' => 'required|integer|in:1,2,3',
            'motivoAnulacion' => 'required|string',
            'codigoGeneracion' => ['required', 'string', Rule::in([$dte->codigoGeneracion])],
            'codigoGeneracionR' => [
                'required_if:tipoAnulacion,1,3',
                'nullable',
                'string',
                'size:36',
                function($attribute, $value, $fail) use ($dte) {
                    if($value != "") {
                        $replacement = dte::query()
                            ->where('codigoGeneracion', $value)
                            ->where('customer_id', $dte->customer_id)
                            ->where('received', 1)
                            ->where('invalidate', 0)
                            ->where('id', '<>', $dte->id)
                            ->first();
                        $issuer = $dte->emisor_nit ?: data_get(json_decode($dte->json_dte, true), 'emisor.nit');
                        $replacementIssuer = $replacement ? ($replacement->emisor_nit ?: data_get(json_decode($replacement->json_dte, true), 'emisor.nit')) : null;
                        if (!$replacement || !$issuer || $issuer !== $replacementIssuer
                            || ($dte->provider ?: 'rrd') !== ($replacement->provider ?: 'rrd')) {
                            $fail('El documento sustituto debe estar certificado, no invalidado y pertenecer al mismo cliente, emisor y proveedor.');
                        }
                    }
                }
            ]
        ]);

        if ($dte->provider === 'infile') {
            return $this->invalidateInfileDte($dte, $request);
        }
        if ($dte->provider && $dte->provider !== 'rrd') {
            return back()->withErrors(['El proveedor del documento no admite invalidacion.']);
        }

        $json = DteSchema::invalidation(json_decode($dte->json_dte, true), $request->only([
            'codigoGeneracion', 'codigoGeneracionR', 'tipoAnulacion', 'motivoAnulacion',
        ]));
        $schema = json_decode(file_get_contents(base_path('resources/fe_schemas/v3/invalidacion-schema-v3.json')));
        $errors = DteSchema::errors(json_decode(json_encode($json)), $schema);

        if (!$errors) {
            $signedInvalidate = $this->signInvalidate($json);
            
            $client = $this->httpClient(['verify' => false]);

            $url = env('API_INVALIDATE_URL');

            $headers = [
                'User-Agent' => '',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->rrdToken(),
            ];

            $params = [
                'ambiente' => env('DTE_ENVIRONMENT'),
                'idEnvio' => 1,
                'version' => $json['identificacion']['version'],
                'nitEmisor' => env('DTE_EMISOR_NIT'),
                'codGen' => $json['identificacion']['codigoGeneracion'],
                'documento' => $signedInvalidate
            ];

            //dd($params);

            try {
                $response = $client->post($url, ['headers' => $headers, 'json' => $params]);
                $responseData = json_decode($response->getBody()->getContents(), true);
    
                if($response->getStatusCode() === 200) {
                    //dd($responseData);
                    switch($responseData['estado']) {
                        case 'PROCESADO':
                            $selloRecibido = $responseData['selloRecibido'];
                            //dd($selloRecibido);

                            $fechaHora =  $responseData['fhProcesamiento'];
                            $fechaHoraFormateada = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $fechaHora)->format('Y-m-d H:i:s');
    
                            $this->recordInvalidation($dte, $selloRecibido, $fechaHoraFormateada);
    
                            //Alert::success('Transmision Realizada', 'El Documento fue enviado Satisfactoriamente');
                            return redirect()->back()->with('message', 'Documento Anulado satisfactoriamente!!');
                        break;
                        case 'RECHAZADO':
                            $rejectedError = 'RECHAZADO: CODIGO ('.$responseData['codigoMsg'].') - '.$responseData['descripcionMsg'];
    
                            return redirect()->back()->with('rejectedError', $rejectedError);
                        break;
                        default:
                            $otherError = 'ERROR: CODIGO ('.$responseData['codigoMsg'].') - '.$responseData['descripcionMsg'];;
                            return redirect()->back()->with('otherError', $otherError);
                        break;
                    }
                }
            } catch(\Exception $e) {
                $exceptionError = $e->getMessage();
                return redirect()->back()->with('exceptionError', $exceptionError);
            }
        } else {

            //return response()->json(['errors' => $errors], 400);
            return redirect('/invalidate/'.$dte->id)->with('errors', $errors);
        }

        //return $request;

        /*$dteJson = json_decode($dte->json_dte);
        
        //return response()->json($params);*/
    } 

    private function recordInvalidation(dte $dte, $stamp, $date)
    {
        $actorColumn = Schema::hasColumn('dtes', 'invalidate_by') ? 'invalidate_by' : 'invalidated_by';
        $dte->forceFill([
            'invalidate' => 1,
            'invalidate_stamp' => $stamp,
            'invalidate_date' => $date,
            $actorColumn => auth()->id(),
        ])->save();
    }

    private function invalidateInfileDte(dte $dte, Request $request)
    {
        $infile = new InfileInvalidation();
        $stage = 'preparacion';
        $status = null;
        $receipt = null;
        $receiptSaved = false;
        $reference = \Illuminate\Support\Str::uuid()->toString();
        try {
            $url = $infile->url($dte);
            if ($infile->environment($dte) === 'prod' && !config('services.infile.invalidation_response_confirmed')) {
                return back()->withErrors(['La invalidacion Infile en produccion requiere confirmar primero el contrato de respuesta con una prueba en Sandbox.']);
            }
            $payload = $infile->payload($dte, $request->only(['tipoAnulacion', 'motivoAnulacion', 'codigoGeneracionR']));
            $headers = $infile->headers($dte);
            $caBundle = $infile->caBundle();
            $stage = 'envio';
            $response = $this->httpClient()->post($url, [
                'headers' => $headers,
                'json' => $payload,
                'http_errors' => false,
                'allow_redirects' => false,
                'connect_timeout' => 10,
                'timeout' => 30,
                'verify' => $caBundle,
            ]);
            $body = $response->getBody()->getContents();
            $responseData = json_decode($body, true);
            $status = $response->getStatusCode();
            $stage = 'guardar_respuesta';
            $receipt = 'infile/invalidations/'.$dte->id.'/'.$reference.'.json';
            $receiptSaved = Storage::disk('local')->put($receipt, json_encode([
                'http_status' => $status,
                'dte_id' => $dte->id,
                'received_at' => now()->toIso8601String(),
                'response' => is_array($responseData) ? $responseData : $body,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            if (!$receiptSaved) {
                throw new \RuntimeException('No se pudo guardar la respuesta de Infile.');
            }

            $stage = 'validar_respuesta';
            if ($status >= 200 && $status < 300 && is_array($responseData)) {
                $confirmed = $infile->confirmedResult($responseData, $dte);
                if ($confirmed) {
                    $stage = 'actualizar_registro';
                    $this->recordInvalidation($dte, $confirmed['stamp'], $confirmed['date']);
                    return back()->with('message', 'Documento anulado satisfactoriamente en Infile.');
                }
            }
            $errors = ['Infile HTTP '.$status.': '.(is_array($responseData) ? data_get($responseData, 'mensaje', 'Respuesta de invalidacion no confirmada.') : 'Respuesta no valida.')];
            foreach ((array) data_get($responseData, 'errores', []) as $error) {
                $errors[] = is_string($error) ? $error : json_encode($error, JSON_UNESCAPED_UNICODE);
            }
            if ($status >= 200 && $status < 300 && data_get($responseData, 'ok') !== false) {
                $errors[] = 'La solicitud pudo haber sido procesada. Verifique su estado en Infile antes de reenviarla; el registro local no fue marcado como invalidado.';
            }
            $errors[] = 'Referencia de respuesta: '.$receipt;
            return back()->withErrors($errors);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors([$e->getMessage()]);
        } catch (\Exception $e) {
            $context = method_exists($e, 'getHandlerContext') ? $e->getHandlerContext() : [];
            $curlError = isset($context['errno']) ? (int) $context['errno'] : null;
            // Never log the raw exception, request headers, payload or SQL bindings.
            Log::warning('Fallo en invalidacion Infile', [
                'reference' => $reference,
                'dte_id' => $dte->id,
                'stage' => $stage,
                'exception_class' => get_class($e),
                'exception_code' => $e->getCode(),
                'curl_errno' => $curlError,
                'http_status' => $status,
                'receipt' => $receiptSaved ? $receipt : null,
            ]);
            $message = 'No se pudo confirmar la invalidacion en Infile.';
            if ($curlError === 60 || $curlError === 77) {
                $message = 'No se pudo validar el certificado HTTPS de Infile. Revise el paquete CA y la configuracion INFILE_CA_BUNDLE.';
            } elseif ($curlError === 28) {
                $message = 'Se agoto el tiempo de espera al comunicarse con Infile.';
            } elseif ($curlError === 6) {
                $message = 'No se pudo resolver el nombre del servidor de Infile.';
            } elseif ($curlError === 7) {
                $message = 'No se pudo establecer conexion con Infile.';
            } elseif ($stage === 'guardar_respuesta') {
                $message = 'Infile respondio, pero no se pudo guardar la respuesta en el almacenamiento local.';
            } elseif ($stage === 'actualizar_registro') {
                $message = 'Infile confirmo la invalidacion, pero fallo la actualizacion del registro local.';
            }
            $errors = [$message, 'Verifique el estado en Infile antes de reenviar la solicitud.', 'Referencia de diagnostico: '.$reference];
            if ($receiptSaved) {
                $errors[] = 'Referencia de respuesta: '.$receipt;
            }
            return back()->withErrors($errors);
        }
    }

    public function signInvalidate($json)
    {
        $client = $this->httpClient(['verify' => false]);

        //dd(json_encode($json));

        $url = env('API_SIGN_URL');
        
        $nit = env('API_SIGN_NIT');
        $passwordPri = env('API_SIGN_PASSWORD');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->rrdToken(),
        ];

        $params = [
            'activo' => true,
            'nit' => $nit,
            'passwordPri' => $passwordPri,
            'dteJson' => $json
        ];

        try {
            $response = $client->post($url, ['headers' => $headers, 'json' => $params]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() === 200) {
                if(isset($responseData['status']) && $responseData['status'] === 'OK') {
                    $firma = $responseData['body'];
                    return $firma;
                } else {
                    //return response()->json('error', 'Error en la respuesta de la API: '.$responseData['message'], 500);
                    $errors[] = 'Error en la respuesta de la API: '.$responseData['message'];
                    return back()->with('errors', $errors);
                }
            }
        } catch(\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            $errors[] = $e->getMessage();
            return back()->with('errors', $errors);
        }
    }

    public function obtenerTokenJWT()
    {
        // Verificar si el token ya esta en cache
        //$token = Cache::get('api_token');
        
        //if($token) {
        //    return $token;
        //}

        // Si el token no esta en cache, hacer una peticion para obtenerlo
        $url = env('API_AUTH_URL');

        $grant_type = env('API_GRANT_TYPE');
        $client_id = env('API_CLIENT_ID');
        $client_secret = env('API_CLIENT_SECRET');
        $resource = env('API_RESOURCE');

        $client = $this->httpClient([
            'verify' => false,
        ]);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $params = [
            'grant_type' => $grant_type,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'resource' => $resource
        ];
        try{
            $response = $client->get($url,['headers' => $headers, 'form_params' => $params]);

            $responseBody = $response->getBody()->getContents();
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $data = json_decode($responseBody, true);

        // Verificar si la peticion devuelve un error
        if(isset($data['status']) && $data['status'] === 'ERROR') {
            // Si hay un error, lanzar una excepcion con el mensaje de error
            throw new \Exception($data['message']);
        }

        $token = $data['access_token'];

        // Almacenar el token en cache con una duracion de 24 horas (86400 segundos)
        //Cache::put('api_token', $token, 3600);

        return $token;
    }
}
