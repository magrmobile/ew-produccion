<?php

namespace App\Services;

use App\dte;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class InfileInvalidation
{
    public function issuerNit(dte $dte)
    {
        $nit = data_get(json_decode($dte->json_dte, true), 'emisor.nit');
        if (!$nit || ($dte->emisor_nit && $dte->emisor_nit !== $nit)) {
            throw new InvalidArgumentException('El NIT del emisor del DTE no coincide con el registro.');
        }
        return $dte->emisor_nit ?: $nit;
    }

    public function payload(dte $dte, array $input)
    {
        $data = json_decode($dte->json_dte, true);
        if (data_get($data, 'identificacion.codigoGeneracion') !== $dte->codigoGeneracion) {
            throw new InvalidArgumentException('El UUID certificado no coincide con el registro del DTE.');
        }
        $nit = $this->issuerNit($dte);
        $prefix = $nit === '05011011221017' ? 'ONEWIRE_DTE_EMISOR' : 'DTE_EMISOR';
        $establishment = data_get($data, 'emisor.codEstable');
        if (!$establishment && $nit === env($prefix.'_NIT')) {
            $establishment = env($prefix.'_CODESTABLE');
        }
        $receiver = data_get($data, 'receptor', data_get($data, 'sujetoRetencion', data_get($data, 'sujetoExcluido', [])));
        $payload = [
            'establecimiento' => $establishment,
            'uuid' => $dte->codigoGeneracion,
            'tipo_anulacion' => (int) $input['tipoAnulacion'],
            'motivo' => $input['motivoAnulacion'],
            'responsable' => [
                'nombre' => data_get($data, 'emisor.nombre'),
                'tipo_documento' => '36',
                'numero_documento' => $nit,
            ],
            'solicitante' => [
                'nombre' => data_get($receiver, 'nombre'),
                'tipo_documento' => data_get($receiver, 'tipoDocumento', '36'),
                'numero_documento' => data_get($receiver, 'numDocumento', data_get($receiver, 'nit')),
                'correo' => data_get($receiver, 'correo'),
            ],
        ];
        if (!empty($input['codigoGeneracionR'])) {
            $payload['nuevo_documento'] = $input['codigoGeneracionR'];
        }
        Validator::make($payload, [
            'establecimiento' => 'required|string',
            'uuid' => 'required|string|size:36',
            'tipo_anulacion' => 'required|integer|in:1,2,3',
            'motivo' => 'required|string',
            'nuevo_documento' => 'required_if:tipo_anulacion,1,3|nullable|string|size:36',
            'responsable.nombre' => 'required|string',
            'responsable.tipo_documento' => 'required|string',
            'responsable.numero_documento' => 'required|string',
            'solicitante.nombre' => 'required|string',
            'solicitante.tipo_documento' => 'required|string',
            'solicitante.numero_documento' => 'required|string',
            'solicitante.correo' => 'required|email',
        ])->validate();
        return ['invalidacion' => $payload];
    }

    public function environment(dte $dte)
    {
        $environment = config('services.infile.environment');
        if (!in_array($environment, ['test', 'prod'], true)) {
            throw new InvalidArgumentException('INFILE_ENVIRONMENT debe ser test o prod.');
        }
        $expected = $environment === 'prod' ? '01' : '00';
        if (data_get(json_decode($dte->json_dte, true), 'identificacion.ambiente') !== $expected) {
            throw new InvalidArgumentException('El ambiente del DTE no coincide con INFILE_ENVIRONMENT.');
        }
        return $environment;
    }

    public function url(dte $dte)
    {
        $environment = $this->environment($dte);
        $path = '/api/v1/certificacion/'.$environment.'/documento/invalidacion';
        $url = config('services.infile.invalidate_url') ?: 'https://certificador.infile.com.sv'.$path;
        if (!filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https'
            || parse_url($url, PHP_URL_HOST) !== 'certificador.infile.com.sv'
            || parse_url($url, PHP_URL_PATH) !== $path) {
            throw new InvalidArgumentException('INFILE_INVALIDATE_URL debe apuntar al endpoint de invalidacion de Infile para el ambiente seleccionado.');
        }
        return $url;
    }

    public function caBundle()
    {
        $path = config('services.infile.ca_bundle') ?: base_path('resources/certificates/cacert.pem');
        if (!is_string($path) || !is_file($path) || !is_readable($path)) {
            throw new InvalidArgumentException('No se puede leer el paquete de certificados CA de Infile. Revise INFILE_CA_BUNDLE.');
        }
        return $path;
    }

    public function headers(dte $dte)
    {
        $key = config('services.infile.api_key');
        if (!$key) {
            throw new InvalidArgumentException('No se ha configurado INFILE_API_KEY.');
        }
        return ['Content-Type' => 'application/json', 'usuario' => $this->issuerNit($dte), 'llave' => $key];
    }

    // Match the processed event to its original DTE, not to the event's own UUID.
    public function confirmedResult(array $response, dte $dte)
    {
        if (!config('services.infile.invalidation_response_confirmed') || data_get($response, 'ok') !== true
            || data_get($response, 'respuesta_dgi.estado') !== 'PROCESADO'
            || data_get($response, 'respuesta_dgi.codigoMsg') !== '001') {
            return null;
        }
        $eventCode = data_get($response, 'json.identificacion.codigoGeneracion');
        $environment = data_get(json_decode($dte->json_dte, true), 'identificacion.ambiente');
        if (!is_string($eventCode) || !preg_match('/^[A-F0-9]{8}(?:-[A-F0-9]{4}){3}-[A-F0-9]{12}$/', $eventCode)
            || data_get($response, 'respuesta.codigoGeneracion') !== $eventCode
            || data_get($response, 'respuesta_dgi.codigoGeneracion') !== $eventCode
            || data_get($response, 'json.documento.codigoGeneracion') !== $dte->codigoGeneracion
            || data_get($response, 'json.emisor.nit') !== $this->issuerNit($dte)
            || data_get($response, 'json.identificacion.ambiente') !== $environment
            || data_get($response, 'respuesta_dgi.ambiente') !== $environment
            || data_get($response, 'json.identificacion.version') !== 3
            || data_get($response, 'respuesta_dgi.version') !== 3) {
            return null;
        }
        $stamp = data_get($response, 'respuesta_dgi.selloRecibido');
        $date = data_get($response, 'respuesta_dgi.fhProcesamiento');
        if (!is_string($stamp) || trim($stamp) === '' || !is_string($date)
            || data_get($response, 'respuesta.selloRecepcion') !== $stamp) {
            return null;
        }
        $parsed = InfileDateTime::databaseValue($date);
        return $parsed ? ['stamp' => $stamp, 'date' => $parsed] : null;
    }
}
