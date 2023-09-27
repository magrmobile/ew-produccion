<?php

namespace App\Documents;

use Illuminate\Support\Facades\DB;

class FacturaElectronica extends DocumentBase
{
    protected $datosReceptor;
    protected $detalleItems;
    protected $detalleResumen;
    //protected $documentoRelacionado;

    public function __construct($datosReceptor, $detalleItems, $detalleResumen/*, $documentoRelacionado*/)
    {
        parent::__construct('01');
        $this->datosReceptor = $datosReceptor;
        $this->detalleItems = $detalleItems;
        $this->detalleResumen = $detalleResumen;
        //$this->documentoRelacionado = $documentoRelacionado;
    }

    public function toArray()
    {
        $data = parent::toArray();
        // Campos Especificos

        // Receptor
        $data['receptor']['tipoDocumento'] = "36";
        $data['receptor']['numDocumento'] = isset($this->datosReceptor['nit']) ? $this->datosReceptor['nit'] : env('DTE_RECEPTOR_NIT');
        $data['receptor']['nrc'] = isset($this->datosReceptor['nrc']) ? $this->datosReceptor['nrc'] : env('DTE_RECEPTOR_NRC');
        $data['receptor']['nombre'] = isset($this->datosReceptor['nombre']) ? $this->datosReceptor['nombre'] : env('DTE_RECEPTOR_NOMBRE');
        $data['receptor']['codActividad'] = isset($this->datosReceptor['codActividad']) ? $this->datosReceptor['codActividad'] : env('DTE_RECEPTOR_CODACTIVIDAD');
        $data['receptor']['descActividad'] = isset($this->datosReceptor['descActividad']) ? $this->datosReceptor['descActividad'] : env('DTE_RECEPTOR_DESCACTIVIDAD');
        $data['receptor']['direccion']['departamento'] = isset($this->datosReceptor['departamento']) ? $this->datosReceptor['departamento'] : env('DTE_RECEPTOR_DIRECCION_DEPARTAMENTO');
        $data['receptor']['direccion']['municipio'] = isset($this->datosReceptor['municipio']) ? $this->datosReceptor['municipio'] : env('DTE_RECEPTOR_DIRECCION_MUNICIPIO');
        $data['receptor']['direccion']['complemento'] = isset($this->datosReceptor['complemento']) ? $this->datosReceptor['complemento'] : env('DTE_RECEPTOR_DIRECCION_COMPLEMENTO');
        $data['receptor']['telefono'] = isset($this->datosReceptor['telefono']) ? $this->datosReceptor['telefono'] : env('DTE_RECEPTOR_TELEFONO');
        $data['receptor']['correo'] =  isset($this->datosReceptor['correo']) ? $this->datosReceptor['correo'] : env('DTE_RECEPTOR_EMAIL');
        
        // Documentos Relacionados
        $data['documentoRelacionado'] = null;
        /*for($i=0; $i < count($this->documentoRelacionado); $i++) {
            $data['documentoRelacionado'][$i]['tipoDocumento'] = "01";
            $data['documentoRelacionado'][$i]['tipoGeneracion'] = 1;
            $data['documentoRelacionado'][$i]['numeroDocumento'] = $this->documentoRelacionado[$i]['numdoc'];
            $data['documentoRelacionado'][$i]['fechaEmision'] = $this->documentoRelacionado[$i]['date'];
        }*/
        

        // Otros Documentos
        $data['otrosDocumentos'] = null;
        
        // Venta Tercero
        $data['ventaTercero'] = null;

        $totalIva = 0;

        // Cuerpo Documento
        for($i=0; $i < count($this->detalleItems); $i++) {
            $cat014 = DB::table('cat014')
            ->where('id', $this->detalleItems[$i]['unidad'])
            ->first();

            if($cat014){
                $unidad = $cat014->id;
            } else {
                $unidad = 99;
            }

            $ivaItem = round(($this->detalleItems[$i]['cantidad'] * $this->detalleItems[$i]['precio']) * 0.13, 2);

            $item = [
                'numItem' => $i + 1,
                'tipoItem' => 1,
                'numeroDocumento' => null,
                'codigo' => null,
                'codTributo' => null,
                'descripcion' => $this->detalleItems[$i]['descripcion'],
                'cantidad' => (float) $this->detalleItems[$i]['cantidad'],
                'uniMedida' => (int) $unidad,
                'precioUni' => (float) $this->detalleItems[$i]['precio'],
                'montoDescu' => 0,
                'ventaNoSuj' => 0,
                'ventaExenta' => 0,
                'ventaGravada' => $this->detalleItems[$i]['monto'],
                'tributos' => null,
                'psv' => 0,
                'noGravado' => 0, 
                'ivaItem' => $ivaItem 
            ];

            $totalIva =+ $ivaItem;
            
            $data['cuerpoDocumento'][] = $item;
        }

        // Resumen
        $monto = round($this->detalleResumen['monto'],2);
        $monto_iva = round($this->detalleResumen['monto'] * 0.13, 2);

        $data['resumen']['totalNoSuj'] = 0.00;
        $data['resumen']['totalExenta'] = 0;
        $data['resumen']['totalGravada'] = $monto;
        $data['resumen']['subTotalVentas'] = $monto;
        $data['resumen']['descuNoSuj'] = 0;
        $data['resumen']['descuExenta'] = 0;
        $data['resumen']['descuGravada'] = 0;
        $data['resumen']['porcentajeDescuento'] = 0;
        $data['resumen']['totalDescu'] = 0;

        $montoTotal = round($monto + $monto_iva, 2);

        if($this->datosReceptor['category_id'] != 1 && $monto >= 100) {
            $ivaPerci1 = round($monto * 0.01, 2);
        } else {
            $ivaPerci1 = 0;
        }

        $data['resumen']['tributos'] = null;
        $data['resumen']['subTotal'] = $this->detalleResumen['monto'];
        $data['resumen']['ivaRete1'] = 0;
        //$data['resumen']['ivaPerci1'] = $ivaPerci1;
        $data['resumen']['reteRenta'] = 0;
        $data['resumen']['montoTotalOperacion'] = round($montoTotal, 2);
        $data['resumen']['totalNoGravado'] = 0;
        $totalPagar = $montoTotal + $ivaPerci1;
        $data['resumen']['totalPagar'] = round($totalPagar, 2);
        $data['resumen']['totalLetras'] = self::numeroALetras($totalPagar);
        $data['resumen']['saldoFavor'] = 0;

        $condicion = DB::table('qb_terms')
            ->where('terms', $this->detalleResumen['condicion'])
            ->first();

        $data['resumen']['condicionOperacion'] = (int) $condicion->codigo_mh;

        $pagos = [
            'codigo' => "01",
            'montoPago' => $montoTotal,
            'plazo' => null,
            'referencia' => "",
            'periodo' => null
        ];
        
        $data['resumen']['pagos'][] = $pagos;
        $data['resumen']['numPagoElectronico'] = null;
        $data['resumen']['totalIva'] = $totalIva;

        // Extension
        $data['extension']['nombEntrega'] = null;
        $data['extension']['docuEntrega'] = null;
        $data['extension']['nombRecibe'] = null;
        $data['extension']['docuRecibe'] = null;
        $data['extension']['observaciones'] = null;
        $data['extension']['placaVehiculo'] = null;

        // Apendice
        $data['apendice'] = null;

        return $data;
    }
}