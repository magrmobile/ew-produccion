<?php

namespace App\Services;

use App\Documents\DocumentBase;
use InvalidArgumentException;
use Opis\JsonSchema\Validator;
use Illuminate\Support\Str;

class DteSchema
{
    const FILES = [
        '01' => 'v2/fe-f-v2.json',
        '03' => 'v4/fe-ccf-v4.json',
        '04' => 'v4/fe-nr-v4.json',
        '05' => 'v4/fe-nc-v4.json',
        '06' => 'v4/fe-nd-v4.json',
        '07' => 'v2/fe-cr-v2.json',
        '11' => 'v3/fe-fex-v3.json',
        '14' => 'v2/fe-fse-v2.json',
    ];

    public static function schema($type)
    {
        if (!isset(self::FILES[$type])) {
            throw new InvalidArgumentException('Tipo de DTE no soportado: '.$type);
        }

        return json_decode(file_get_contents(base_path('resources/fe_schemas/'.self::FILES[$type])));
    }

    public static function normalize(array $data, $district = null)
    {
        $type = $data['identificacion']['tipoDte'];
        $schema = self::schema($type);
        $data['identificacion']['version'] = $schema->properties->identificacion->properties->version->const;
        $data['identificacion']['motivoContin'] = data_get($data, 'identificacion.motivoContin', data_get($data, 'identificacion.motivoContigencia'));

        if (isset($data['receptor']['direccion']) && $district !== null) {
            $data['receptor']['direccion']['distrito'] = (string) $district;
        }

        foreach (['ivaPerci1' => 'ivaPerci', 'ivaRete1' => 'ivaRete'] as $old => $new) {
            if (array_key_exists($old, $data['resumen'])) {
                $data['resumen'][$new] = $data['resumen'][$old];
            }
        }
        $data['resumen']['observaciones'] = data_get($data, 'extension.observaciones', data_get($data, 'resumen.observaciones')) ?: null;

        if (in_array($type, ['05', '06'])) {
            if (isset($data['receptor']['nit'])) {
                $data['receptor']['tipoDocumento'] = '36';
                $data['receptor']['numDocumento'] = $data['receptor']['nit'];
            }
            $totalIva = 0;
            foreach ($data['cuerpoDocumento'] as &$item) {
                $item['noGravado'] = isset($item['noGravado']) ? $item['noGravado'] : 0;
                $item['ivaPerci'] = isset($item['ivaPerci']) ? $item['ivaPerci'] : 0;
                $item['ivaRete'] = isset($item['ivaRete']) ? $item['ivaRete'] : 0;
                $item['totalIva'] = isset($item['totalIva']) ? $item['totalIva'] : round($item['ventaGravada'] * 0.13, 8);
                $totalIva += $item['totalIva'];
            }
            unset($item);
            $data['resumen']['totalIva'] = round($totalIva, 2);
            $data['resumen']['totalNoGravado'] = round(array_sum(array_column($data['cuerpoDocumento'], 'noGravado')), 2);
            $data['resumen']['totalPagar'] = round($data['resumen']['montoTotalOperacion'] + $data['resumen']['totalNoGravado'] + $data['resumen']['ivaPerci'] - $data['resumen']['ivaRete'], 2);
        }

        if ($type === '07') {
            $data['emisor']['codEstable'] = data_get($data, 'emisor.codEstable', data_get($data, 'emisor.codigo'));
            $data['emisor']['codPuntoVenta'] = data_get($data, 'emisor.codPuntoVenta', data_get($data, 'emisor.puntoVenta'));
            foreach ($data['cuerpoDocumento'] as &$item) {
                $item['tipoGeneracion'] = data_get($item, 'tipoGeneracion', data_get($item, 'tipoDoc'));
                $item['numeroDocumento'] = data_get($item, 'numeroDocumento', data_get($item, 'numDocumento'));
            }
            unset($item);
            $data['resumen']['totalIvaRetenido'] = data_get($data, 'resumen.totalIvaRetenido', data_get($data, 'resumen.totalIVAretenido'));
            $data['resumen']['totalIva'] = round(array_sum(array_column($data['cuerpoDocumento'], 'montoSujetoGrav')) * 0.13, 2);
            $data['resumen']['totalLetras'] = DocumentBase::numeroALetras($data['resumen']['totalIvaRetenido']);
        }

        if ($type === '11') {
            if (empty($data['documentoRelacionado'])) {
                $data['documentoRelacionado'] = null;
            }
            $data['resumen']['descuGravada'] = data_get($data, 'resumen.descuGravada', data_get($data, 'resumen.descuento', 0));
            $data['resumen']['tributos'] = data_get($data, 'resumen.tributos');
            $data['resumen']['totalNoOnerosas'] = data_get($data, 'resumen.totalNoOnerosas', 0);
            $data['resumen']['saldoFavor'] = data_get($data, 'resumen.saldoFavor', 0);
            foreach ($data['cuerpoDocumento'] as &$item) {
                $item['tipoItem'] = data_get($item, 'tipoItem', (int) data_get($data, 'emisor.tipoItemExpor', 1));
                $item['numeroDocumento'] = data_get($item, 'numeroDocumento');
                $item['codTributo'] = data_get($item, 'codTributo');
            }
            unset($item);
        }

        return self::project($data, $schema);
    }

    // Remove obsolete properties, while leaving missing non-nullable data for validation.
    private static function project($data, $schema)
    {
        if (!is_array($data)) {
            return $data;
        }
        if (isset($schema->properties)) {
            $properties = (array) $schema->properties;
            $data = array_intersect_key($data, $properties);
            foreach ($properties as $key => $property) {
                if (!array_key_exists($key, $data) && in_array($key, isset($schema->required) ? $schema->required : []) && in_array('null', (array) data_get($property, 'type', []))) {
                    $data[$key] = null;
                }
                if (array_key_exists($key, $data)) {
                    $data[$key] = self::project($data[$key], $property);
                }
            }
        } elseif (isset($schema->items)) {
            foreach ($data as &$item) {
                $item = self::project($item, $schema->items);
            }
            unset($item);
        }
        return $data;
    }

    public static function errors($data, $schema)
    {
        $result = (new Validator())->dataValidation($data, $schema, 100);
        $messages = [];
        foreach ($result->getErrors() as $error) {
            self::errorMessages($error, $messages);
        }
        return $messages;
    }

    public static function invalidation(array $source, array $input)
    {
        $emisor = $source['emisor'];
        $prefix = $emisor['nit'] === '05011011221017' ? 'ONEWIRE_DTE_EMISOR' : 'DTE_EMISOR';
        $establishment = data_get($emisor, 'codEstable') ?: env($prefix.'_CODESTABLE');
        $pointOfSale = data_get($emisor, 'codPuntoVenta') ?: env($prefix.'_CODPUNTOVENTA');
        $receptor = data_get($source, 'receptor', data_get($source, 'sujetoRetencion', data_get($source, 'sujetoExcluido')));
        return [
            'identificacion' => [
                'version' => 3,
                'ambiente' => $source['identificacion']['ambiente'],
                'codigoGeneracion' => Str::upper(Str::uuid()->toString()),
                'fecEmi' => date('Y-m-d'),
                'horEmi' => date('H:i:s'),
                'fusion' => null,
            ],
            'emisor' => [
                'nit' => $emisor['nit'],
                'nombre' => $emisor['nombre'],
                'codEstableMH' => data_get($emisor, 'codEstableMH') ?: env($prefix.'_CODESTABLEMH', $establishment),
                'codEstable' => $establishment,
                'codPuntoVentaMH' => data_get($emisor, 'codPuntoVentaMH') ?: env($prefix.'_CODPUNTOVENTAMH', $pointOfSale),
                'codPuntoVenta' => $pointOfSale,
                'telefono' => $emisor['telefono'],
                'correo' => $emisor['correo'],
            ],
            'documento' => [
                'tipoDte' => $source['identificacion']['tipoDte'],
                'codigoGeneracion' => $input['codigoGeneracion'],
                'selloRecibido' => $source['selloRecibido'],
                'numeroControl' => $source['identificacion']['numeroControl'],
                'fecEmi' => $source['identificacion']['fecEmi'],
                'codigoGeneracionR' => !empty($input['codigoGeneracionR']) ? $input['codigoGeneracionR'] : null,
                'tipoDocumento' => data_get($receptor, 'tipoDocumento', '36'),
                'numDocumento' => data_get($receptor, 'numDocumento', data_get($receptor, 'nit')),
                'nombre' => $receptor['nombre'],
                'telefono' => $receptor['telefono'],
                'correo' => $receptor['correo'],
            ],
            'motivo' => [
                'tipoAnulacion' => (int) $input['tipoAnulacion'],
                'motivoAnulacion' => $input['motivoAnulacion'],
                'nombreResponsable' => $emisor['nombre'],
                'tipDocResponsable' => '36',
                'numDocResponsable' => $emisor['nit'],
                'nombreSolicita' => $receptor['nombre'],
                'tipDocSolicita' => data_get($receptor, 'tipoDocumento', '36'),
                'numDocSolicita' => data_get($receptor, 'numDocumento', data_get($receptor, 'nit')),
            ],
        ];
    }

    private static function errorMessages($error, array &$messages)
    {
        if ($error->subErrors()) {
            foreach ($error->subErrors() as $child) {
                self::errorMessages($child, $messages);
            }
            return;
        }
        $messages[] = "Error en '".implode('.', $error->dataPointer())."': ".$error->keyword().' '.json_encode($error->keywordArgs(), JSON_UNESCAPED_UNICODE);
    }
}
