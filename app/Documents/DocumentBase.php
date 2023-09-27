<?php

namespace App\Documents;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

use Luecano\NumeroALetras\NumeroALetras;

class DocumentBase 
{
    protected $type;
    protected $identificacion = [];
    protected $documentoRelacionado = [];
    protected $emisor = [];
    protected $receptor = [];
    protected $otrosDocumentos = [];
    protected $ventaTercero = [];
    protected $cuerpoDocumento = [];
    protected $resumen = [];
    protected $extension = [];
    protected $apendice = [];
    // protected $firmaElectronica;
    // protected $selloRecibido;
    
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
    }

    public function setDocumentoRelacionado($documentoRelacionado)
    {
        $this->documentoRelacionado = $documentoRelacionado;
    }

    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
    }

    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
    }

    public function setOtrosDocumentos($otrosDocumentos)
    {
        $this->otrosDocumentos = $otrosDocumentos;
    }

    public function setVentaTercero($ventaTercero)
    {
        $this->ventaTercero = $ventaTercero;
    }

    public function setCuerpoDocumento($cuerpoDocumento)
    {
        $this->cuerpoDocumento = $cuerpoDocumento;
    }

    public function setResumen($resumen)
    {
        $this->resumen = $resumen;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function setApendice($apendice)
    {
        $this->apendice = $apendice;
    }

    /* public function setFirmaElectronica($firmaElectronica)
    {
        $this->firmaElectronica = $firmaElectronica;
    }

    public function setSelloRecibido($selloRecibido)
    {
        $this->selloRecibido = $selloRecibido;
    }*/

    public function toArray()
    {
        switch($this->type) {
            case '03':
            case '04':
            case '05':
            case '06':
                $version = 3;
                break;
            default:
                $version = 1;
                break;
        }

        $numeroControl = "DTE-".$this->type."-".env('DTE_ESTABLECIMIENTO').env('DTE_PUNTOVENTA')."-".$this->generarCodigo();
        $codigoGeneracion = Str::upper(Str::uuid()->toString());

        $data = [
            'identificacion' => [
                'version' => $version,
                'ambiente' => env('DTE_ENVIRONMENT'),
                'tipoDte' => $this->type,
                'numeroControl' => $numeroControl,
                'codigoGeneracion' => $codigoGeneracion,
                'tipoModelo' => 1,
                'tipoOperacion' => 1,
                'tipoContingencia' => null,
                'motivoContin' => null,
                'fecEmi' => date('Y-m-d'),
                'horEmi' => date('H:i:s'),
                'tipoMoneda' => 'USD'
            ],
            'documentoRelacionado' => $this->documentoRelacionado,
            //'documentoRelacionado' => null,
            'emisor' => [
                'nit' => env('DTE_EMISOR_NIT'),
                'nrc' => env('DTE_EMISOR_NRC'),
                'nombre' => env('DTE_EMISOR_NOMBRE'),
                'codActividad' => env('DTE_EMISOR_CODACTIVIDAD'),
                'descActividad' => env('DTE_EMISOR_DESCACTIVIDAD'),
                'nombreComercial' => env('DTE_EMISOR_NOMBRECOMERCIAL'),
                'tipoEstablecimiento' => env('DTE_EMISOR_TIPOESTABLECIMIENTO'),
                'direccion' => [
                    'departamento' => env('DTE_EMISOR_DIRECCION_DEPARTAMENTO'),
                    'municipio' => env('DTE_EMISOR_DIRECCION_MUNICIPIO'),
                    'complemento' => env('DTE_EMISOR_DIRECCION_COMPLEMENTO')
                ],
                'telefono' => env('DTE_EMISOR_TELEFONO'),
                'correo' => env('DTE_EMISOR_EMAIL'),
                'codEstableMH' => env('DTE_EMISOR_CODESTABLEMH'),
                'codEstable' => env('DTE_EMISOR_CODESTABLE'),
                'codPuntoVentaMH' => env('DTE_EMISOR_CODPUNTOVENTAMH'),
                'codPuntoVenta' => env('DTE_EMISOR_CODPUNTOVENTA'),
            ],
            'receptor' => $this->receptor,
            'otrosDocumentos' => $this->otrosDocumentos,
            'ventaTercero' => $this->ventaTercero,
            'cuerpoDocumento' => $this->cuerpoDocumento,
            'resumen' => $this->resumen,
            'extension' => $this->extension,
            'apendice' => $this->apendice,
            //'firmaElectronica' => $this->firmaElectronica,
            //'selloRecibido' => $this->selloRecibido,
        ];

        return $data;
    }

    private function generarCodigo()
    {
        $year = now()->year;
        $codigo = '';

        $createdBy = Auth::id();

        // Obtener el último código generado para la fecha actual
        $ultimoCodigo = DB::table('documents')
            ->whereYear('created_at', $year)
            ->orderByDesc('sequential_code')
            ->select('sequential_code')
            ->first();

        if ($ultimoCodigo) {
            $codigo = str_pad((int)$ultimoCodigo->sequential_code + 1, 15, '0', STR_PAD_LEFT);
        } else {
            $codigo = '000000000000001';
        }

        // Guardar el código en la tabla documentos
        DB::table('documents')->insert([
            'sequential_code' => $codigo,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $createdBy,
        ]);

        return $codigo;
    }

    public static function numeroALetras($numero)
    {
        $formatter = new NumeroALetras();

        return $formatter->toMoney($numero, 2, 'DOLARES', 'CENTAVOS');
    }
}