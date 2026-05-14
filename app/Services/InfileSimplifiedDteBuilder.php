<?php

namespace App\Services;

class InfileSimplifiedDteBuilder
{
    public function build($dteJson)
    {
        $data = is_array($dteJson) ? $dteJson : json_decode(json_encode($dteJson), true);

        $documento = [
            'tipo_dte' => data_get($data, 'identificacion.tipoDte'),
            'establecimiento' => data_get($data, 'emisor.codEstable'),
            'punto_venta' => data_get($data, 'emisor.codPuntoVenta'),
            'condicion_pago' => data_get($data, 'resumen.condicionOperacion'),
            'actividad_economica' => data_get($data, 'emisor.codActividad'),
            'uuid' => data_get($data, 'identificacion.codigoGeneracion'),
            'numero_control' => data_get($data, 'identificacion.numeroControl'),
            'fecha_emision' => data_get($data, 'identificacion.fecEmi'),
            'hora_emision' => data_get($data, 'identificacion.horEmi'),
            'descuento_no_sujeto' => data_get($data, 'resumen.descuNoSuj'),
            'descuento_exentas' => data_get($data, 'resumen.descuExenta'),
            'descuento_gravadas' => data_get($data, 'resumen.descuGravada'),
            'porcentaje_descuento' => data_get($data, 'resumen.porcentajeDescuento'),
            'renta_retenida' => data_get($data, 'resumen.reteRenta'),
            'retener_iva' => (float) data_get($data, 'resumen.ivaRete1', 0) > 0,
            'numero_pago_electronico' => data_get($data, 'resumen.numPagoElectronico'),
            'documentos_relacionados' => $this->documentosRelacionados(data_get($data, 'documentoRelacionado')),
            'receptor' => $this->receptor($data),
            'items' => $this->items(data_get($data, 'cuerpoDocumento', [])),
            'pagos' => $this->pagos(data_get($data, 'resumen.pagos', [])),
            'extension' => $this->extension(data_get($data, 'extension', [])),
            'apendice' => $this->apendice(data_get($data, 'apendice', [])),
        ];

        if (data_get($data, 'identificacion.tipoDte') === '11') {
            $documento['recinto_fiscal'] = data_get($data, 'emisor.recintoFiscal');
            $documento['regimen'] = data_get($data, 'emisor.regimen');
            $documento['tipo_item_exportacion'] = data_get($data, 'emisor.tipoItemExpor');
            $documento['incoterms'] = data_get($data, 'resumen.codIncoterms');
        }

        return [
            'documento' => $this->clean($documento),
        ];
    }

    private function receptor(array $data)
    {
        $receptor = data_get($data, 'receptor');

        if (!$receptor) {
            $receptor = data_get($data, 'sujetoRetencion');
        }

        if (!$receptor) {
            $receptor = data_get($data, 'sujetoExcluido');
        }

        if (!$receptor) {
            return null;
        }

        return $this->clean([
            'nombre' => data_get($receptor, 'nombre'),
            'tipo_documento' => data_get($receptor, 'tipoDocumento', data_get($receptor, 'tipoDoc')),
            'numero_documento' => data_get($receptor, 'numDocumento', data_get($receptor, 'nit')),
            'nrc' => data_get($receptor, 'nrc'),
            'codigo_actividad' => data_get($receptor, 'codActividad'),
            'direccion' => [
                'departamento' => data_get($receptor, 'direccion.departamento'),
                'municipio' => data_get($receptor, 'direccion.municipio'),
                'complemento' => data_get($receptor, 'direccion.complemento', data_get($receptor, 'complemento')),
            ],
            'telefono' => data_get($receptor, 'telefono'),
            'correo' => data_get($receptor, 'correo'),
        ]);
    }

    private function documentosRelacionados($documentos)
    {
        if (!$documentos || !is_array($documentos)) {
            return null;
        }

        $relacionados = [];

        foreach ($documentos as $documento) {
            $relacionados[] = $this->clean([
                'tipo_documento' => data_get($documento, 'tipoDocumento'),
                'tipo_generacion' => data_get($documento, 'tipoGeneracion'),
                'numero_documento' => data_get($documento, 'numeroDocumento'),
                'fecha_emision' => data_get($documento, 'fechaEmision'),
            ]);
        }

        return $relacionados;
    }

    private function items($items)
    {
        $simplificados = [];

        foreach ((array) $items as $item) {
            $simplificados[] = $this->clean([
                'tipo' => data_get($item, 'tipoItem'),
                'cantidad' => data_get($item, 'cantidad'),
                'unidad_medida' => data_get($item, 'uniMedida'),
                'descripcion' => data_get($item, 'descripcion'),
                'precio_unitario' => data_get($item, 'precioUni'),
                'tipo_venta' => $this->tipoVenta($item),
                'numero_documento' => data_get($item, 'numeroDocumento'),
                'codigo' => data_get($item, 'codigo'),
                'descuento' => data_get($item, 'montoDescu'),
                'psv' => data_get($item, 'psv'),
                'tributos' => $this->tributos(data_get($item, 'tributos')),
            ]);
        }

        return $simplificados;
    }

    private function tipoVenta($item)
    {
        if ((float) data_get($item, 'ventaNoSuj', 0) > 0) {
            return '2';
        }

        if ((float) data_get($item, 'ventaExenta', 0) > 0) {
            return '3';
        }

        if ((float) data_get($item, 'noGravado', 0) > 0) {
            return '4';
        }

        return '1';
    }

    private function tributos($tributos)
    {
        if (!$tributos || !is_array($tributos)) {
            return null;
        }

        $simplificados = [];

        foreach ($tributos as $tributo) {
            if (is_array($tributo)) {
                $simplificados[] = $this->clean([
                    'codigo' => data_get($tributo, 'codigo'),
                    'monto' => data_get($tributo, 'valor'),
                ]);
            } else {
                $simplificados[] = [
                    'codigo' => $tributo,
                ];
            }
        }

        return $simplificados;
    }

    private function pagos($pagos)
    {
        if (!$pagos || !is_array($pagos)) {
            return null;
        }

        $simplificados = [];

        foreach ($pagos as $pago) {
            $simplificados[] = $this->clean([
                'tipo' => data_get($pago, 'codigo'),
                'monto' => data_get($pago, 'montoPago'),
                'referencia' => data_get($pago, 'referencia'),
                'plazo' => data_get($pago, 'plazo'),
                'periodo' => data_get($pago, 'periodo'),
            ]);
        }

        return $simplificados;
    }

    private function extension($extension)
    {
        return $this->clean([
            'nombre_entrega' => data_get($extension, 'nombEntrega'),
            'documento_entrega' => data_get($extension, 'docuEntrega'),
            'nombre_recibe' => data_get($extension, 'nombRecibe'),
            'documento_recibe' => data_get($extension, 'docuRecibe'),
            'observaciones' => data_get($extension, 'observaciones'),
            'placa_vehiculo' => data_get($extension, 'placaVehiculo'),
        ]);
    }

    private function apendice($apendice)
    {
        if (!$apendice || !is_array($apendice)) {
            return null;
        }

        $simplificados = [];

        foreach ($apendice as $item) {
            $simplificados[] = $this->clean([
                'campo' => data_get($item, 'campo'),
                'etiqueta' => data_get($item, 'etiqueta'),
                'valor' => data_get($item, 'valor'),
            ]);
        }

        return $simplificados;
    }

    private function clean($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $item = $this->clean($item);
            }

            if ($item === null || $item === '' || $item === []) {
                unset($value[$key]);
                continue;
            }

            $value[$key] = $item;
        }

        return $value;
    }
}
