<?php

namespace App\Http\Controllers;

use App\Customer;
use App\dte;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class dteController extends Controller
{
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
    public function show($id)
    {
        $dte = dte::where('id',$id)->first();
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

    public function signDte($id)
    {
        $client = new Client();

        $dte = dte::where('id', $id)->first();

        $dteJson = $dte->json_dte;

        //dd($dteJson);

        $url = env('API_SIGN_URL');
        $nit = env('API_SIGN_NIT');
        $passwordPri = env('API_SIGN_PASSWORD');

        $params = [
            'nit' => $nit,
            'activo' => true,
            'passwordPri' => $passwordPri,
        ];

        try {
            $response = $client->post($url, [
                'form_params' => $params,
                'dteJson' => $dteJson
            ]);

            dd($response);

            if($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(), true);

                if(isset($responseData['status']) && $response['status'] === 'success') {
                    $dteJson['firmaElectronica'] = $responseData['body'];
                } else {
                    return back()->with('error', 'Error en la respuesta de la API: '.$responseData['message']);
                }
            }
        } catch(\Exception $e) {
            return back()->with('error', 'Ocurrió un error en la solicitud: ' . $e->getMessage());
        }
    }

    public function obtenerTokenJWT()
    {
        // Verificar si el token ya esta en cache
        $token = Cache::get('api_token');
        if($token) {
            return $token;
        }

        // Si el token no esta en cache, hacer una peticion para obtenerlo
        $user = config('API_USER');
        $password = config('API_PASSWORD');

        $client = new Client();
        $response = $client->post(config('API_AUTH_URL'), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'Domo/1.0',
            ],
            'form-params' => [
                'user' => $user,
                'pwd' => $password
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Verificar si la peticion devuelve un error
        if(isset($data['status']) && $data['status'] === 'ERROR') {
            // Si hay un error, lanzar una excepcion con el mensaje de error
            throw new \Exception($data['message']);
        }

        $token = $data['token'];

        // Almacenar el token en cache con una duracion de 24 horas (86400 segundos)
        Cache::put('api_token', $token, 86400);

        return $token;
    }

    public function recepcionDTE($json) {
        $token = $this->obtenerTokenJWT();
    }
}
