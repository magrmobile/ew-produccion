<?php

namespace App\Http\Controllers;

use App\Customer;
use App\dte;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\InfileSimplifiedDteBuilder;

use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpKernel\Event\ViewEvent;

use JsonSchema\Validator;

class dteController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->token = $this->obtenerTokenJWT();
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
        $client = new Client(['verify' => false]);

        $url = env('API_RECEIVED_URL');

        $headers = [
            'User-Agent' => '',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->token,
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
        $client = new Client(['verify' => false]);
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
                $receivedDate = data_get($responseData, 'respuesta_dgi.fhProcesamiento');

                if ($receivedDate) {
                    $receivedDate = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $receivedDate)->format('Y-m-d H:i:s');
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

        $client = new Client(['verify' => false]);

        $dteJson = json_decode($dte->json_dte);

        $url = env('API_SIGN_URL');
        
        $nit = env('API_SIGN_NIT');
        $passwordPri = env('API_SIGN_PASSWORD');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->token,
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
        $request->validate([
            'tipoAnulacion' => 'required',
            'motivoAnulacion' => 'required',
            'codigoGeneracion' => [
                'required_if:tipoAnulacion,1,3',
                'sometimes',
                function($attribute, $value, $fail) use ($dte) {
                    if($value != "") {
                        $exists = DB::table('dtes')
                            ->where('codigoGeneracion', $value)
                            ->where('customer_id', $dte->customer_id)
                            ->exists();
                        
                        if(!$exists) {
                            $fail("El campo $attribute no existe para el customer_id de $dte->customer_id en la tabla dtes.");
                        }
                    }
                }
            ]
        ]);

        $json = json_decode($dte->json_dte, true);
        $tipoDte = $json["identificacion"]["tipoDte"];

        unset($json["emisor"]["nrc"]);
        unset($json["emisor"]["regimen"]);
        unset($json["emisor"]["direccion"]);
        unset($json["emisor"]["codActividad"]);
        unset($json["emisor"]["descActividad"]);
        unset($json["emisor"]["recintoFiscal"]);
        unset($json["emisor"]["tipoItemExpor"]);
        unset($json["emisor"]["nombreComercial"]);

        $json['emisor']['nomEstablecimiento'] = 'PROVISIONAL';

        
        unset($json["apendice"]);

        if(in_array($tipoDte, ["03","05","06"])) {
            $json["documento"]["tipoDocumento"] = "37";
            $json["documento"]["numDocumento"] = $json["receptor"]["nit"];
        } else {
            $json["documento"]["tipoDocumento"] = $json["receptor"]["tipoDocumento"];
            $json["documento"]["numDocumento"] = $json["receptor"]["numDocumento"];
        }
        
        $json["documento"]["nombre"] = $json["receptor"]["nombre"];
        $json["documento"]["telefono"] = $json["receptor"]["telefono"];
        $json["documento"]["correo"] = $json["receptor"]["correo"];

        unset($json["receptor"]);
        unset($json["ventaTercero"]);

        $json["documento"]["selloRecibido"] = $json["selloRecibido"];
        unset($json["selloRecibido"]);

        $json["documento"]["fecEmi"] = $json["identificacion"]["fecEmi"];

        unset($json["identificacion"]["fecEmi"]);
        unset($json["identificacion"]["horEmi"]);
        $json["documento"]["tipoDte"] = $json["identificacion"]["tipoDte"];

        $dte_monto = array("03","01","11");

        if(in_array($json["identificacion"]["tipoDte"], $dte_monto)) {
            $json["documento"]["montoIva"] = $json["resumen"]["montoTotalOperacion"];
        } else {
            $json["documento"]["montoIva"] = 0.00;
        }

        unset($json["identificacion"]["tipoDte"]);
        
        $json["identificacion"]["version"] = 2;

        $json["documento"]["codigoGeneracion"] = $json["identificacion"]["codigoGeneracion"];
        $json["documento"]["codigoGeneracionR"] = $request->codigoGeneracion;

        unset($json["identificacion"]["tipoModelo"]);
        unset($json["identificacion"]["tipoMoneda"]);

        $json["documento"]["numeroControl"] = $json["identificacion"]["numeroControl"];
        unset($json["identificacion"]["numeroControl"]);
        unset($json["identificacion"]["tipoOperacion"]);
        unset($json["identificacion"]["tipoContingencia"]);
        unset($json["identificacion"]["motivoContigencia"]);
        unset($json["identificacion"]["motivoContin"]);

        $json["identificacion"]["fecAnula"] = date('Y-m-d');
        $json["identificacion"]["horAnula"] = date('H:i:s');

        unset($json["cuerpoDocumento"]);
        unset($json["otrosDocumentos"]);
        unset($json["firmaElectronica"]);
        unset($json["documentoRelacionado"]);
        unset($json["extension"]);

        unset($json["resumen"]);

        $json["motivo"]["tipoAnulacion"] = (int) $request->tipoAnulacion;
        $json["motivo"]["motivoAnulacion"] = $request->motivoAnulacion;
        $json["motivo"]["nombreResponsable"] = $json["emisor"]["nombre"];
        $json["motivo"]["tipDocResponsable"] = "37";
        $json["motivo"]["numDocResponsable"] = $json["emisor"]["nit"];
        //$json["motivo"]["nombreSolicita"] = auth()->user()->name;
        $json["motivo"]["nombreSolicita"] = $json["documento"]["nombre"];
        //$json["motivo"]["tipDocSolicita"] = "36";
        $json["motivo"]["tipDocSolicita"] = $json["documento"]["tipoDocumento"];
        //$json["motivo"]["numDocSolicita"] = auth()->user()->numDocumento;
        $json["motivo"]["numDocSolicita"] = $json["documento"]["numDocumento"];

        $json_encode = json_encode($json);

        $schema_file = base_path('resources/fe_schemas/anulacion-schema-v2.json');
        $schema = json_decode(file_get_contents($schema_file), true);

        $schema_encode = json_encode($schema);

        // Validar el JSON contra el JSON Schema
        $validator = new Validator();
        $validator->validate($json_encode, $schema_encode);

        if($validator->isValid()) {
            $signedInvalidate = $this->signInvalidate($json);
            
            $client = new Client(['verify' => false]);

            $url = env('API_INVALIDATE_URL');

            $headers = [
                'User-Agent' => '',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->token,
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
    
                            $dte->update([
                                'invalidate' => 1,
                                'invalidate_stamp' => $selloRecibido,
                                'invalidate_by' => auth()->user()->id,
                                'invalidate_date' => $fechaHoraFormateada
                            ]);
    
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
            foreach($validator->getErrors() as $error) {
                $errors[] = "Error en '{$error['property']}': {$error['message']}";
            }

            //return response()->json(['errors' => $errors], 400);
            return redirect('/invalidate/'.$dte->id)->with('errors', $errors);
        }

        //return $request;

        /*$dteJson = json_decode($dte->json_dte);
        
        //return response()->json($params);*/
    } 

    public function signInvalidate($json)
    {
        $client = new Client(['verify' => false]);

        //dd(json_encode($json));

        $url = env('API_SIGN_URL');
        
        $nit = env('API_SIGN_NIT');
        $passwordPri = env('API_SIGN_PASSWORD');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->token,
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

        $client = new Client([
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
