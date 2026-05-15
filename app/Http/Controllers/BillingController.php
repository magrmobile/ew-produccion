<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use App\Customer;
use App\dte;

use App\Documents\FacturaElectronica;
use App\Documents\FacturaExportacionElectronica;
use App\Documents\FacturaSujetoExcluidoElectronica;
use App\Documents\DocumentBase;
use App\Documents\NotaCreditoElectronica;
use App\Documents\NotaDebitoElectronica;
use App\Documents\NotaRemisionElectronica;
use App\Documents\ComprobanteCreditoFiscalElectronico;
use App\Documents\ComprobanteRetencionElectronico;

use JsonSchema\Validator;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use App\Services\InfileSimplifiedDteBuilder;

use Exception;

class BillingController extends Controller
{
    public function index() {
        $recintos = DB::table('cat027')->get();
        $regimenes = DB::table('cat028')->get();
        return view('upload', compact('recintos', 'regimenes'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'company' => 'required|in:enerwire,onewire',
            'file' => 'required',
            'type' => 'required|in:01,03,04,05,06,07,11,14'
        ]);

        $file = $request->file('file');

        $csvOriginalName = $file->getClientOriginalName();

        $file->storeAs('csv', $csvOriginalName,'public');

        // Procesar el archivo Excel y generar el JSON correspondiente
        try{
            $issuer = $this->resolveIssuer($request->input('company'));
            DocumentBase::consumeLocalCorrelatives($issuer['provider'] !== 'infile');
            $json = $this->processExcel($file, $request->input('type'), $request);
            DocumentBase::consumeLocalCorrelatives(true);
            $json = $this->applyIssuerToJson($json, $issuer);
            //dd($json);
 
            // Cargar el JSON Schema
            switch($request->type) {
                // FCE - Factura Electrónica
                case '01':
                    $schema_file = base_path('resources/fe_schemas/fe-fc-v1.json');
                    $pdf_template = 'pdf.fe';
                    break;
                // CCFE - Comprobante de Credito Fiscal Electrónico
                case '03':
                    $schema_file = base_path('resources/fe_schemas/fe-ccf-v3.json');
                    $pdf_template = 'pdf.ccf';
                    break;
                // NRE - Nota de Remision Electrónica   
                case '04':
                    $schema_file = base_path('resources/fe_schemas/fe-nr-v3.json');
                    $pdf_template = 'pdf.nr';
                    break;
                // NCE - Nota de Credito Electrónica
                case '05':
                    $schema_file = base_path('resources/fe_schemas/fe-nc-v3.json');
                    $pdf_template = 'pdf.nc';
                    break;
                // NDE - Nota de Debito Electrónica
                case '06':
                    $schema_file = base_path('resources/fe_schemas/fe-nd-v3.json');
                    $pdf_template = 'pdf.nd';
                    break;
                // CRE - Comprobante de Retención Electrónico
                case '07':
                    $schema_file = base_path('resources/fe_schemas/fe-cr-v1.json');
                    $pdf_template = 'pdf.cr';
                    break;
                // FEXE - Factura de Exportación Electrónica
                case '11':
                    $schema_file = base_path('resources/fe_schemas/fe-fex-v1.json');
                    $pdf_template = 'pdf.fexe';
                    break;
                // FSEE - Factura de Sujeto Excluido Electrónica
                case '14':
                    $schema_file = base_path('resources/fe_schemas/fe-fse-v1.json');
                    $pdf_template = 'pdf.fse';
                    break;
                default:
                    // Manejo de error si el tipo de documento no es válido
                    break;
            }

            $schema = json_decode(file_get_contents($schema_file));
            
            // JSON generado
            $json_decode = json_decode($json);
            //dd(json_decode($json));

            // Validar el JSON contra el JSON Schema
            $validator = new Validator();
            $validator->validate($json_decode, $schema);

            //dd($validator);

            if($validator->isValid()) {
                $data = $json_decode;
                $nombre_contacto = "";
                $numdoc_contacto = "";

                if(isset($data->emisor->direccion)) {
                    $dir_emi = [
                        'desc_depto' => DB::table('cat012')->where('id', $data->emisor->direccion->departamento)->first()->valor,
                        'desc_muni' => ucwords(strtolower(DB::table('cat013')->where('id', $data->emisor->direccion->municipio)->where('departamento', $data->emisor->direccion->departamento)->first()->valor)),
                    ];
                }

                if(isset($data->receptor->direccion)) {
                    $dir_rec = [
                        'desc_depto' => DB::table('cat012')->where('id', $data->receptor->direccion->departamento)->first()->valor,
                        'desc_muni' => ucwords(strtolower(DB::table('cat013')->where('id', $data->receptor->direccion->municipio)->where('departamento', $data->receptor->direccion->departamento)->first()->valor)),
                    ];
                }

                if(isset($data->receptor->tipoDocumento)) {
                    $tipo_doc = DB::table('cat022')->where('id', $data->receptor->tipoDocumento)->first()->valor;
                    if($data->receptor->tipoDocumento == "36"){
                        $customer = Customer::where('nit',$data->receptor->numDocumento)->first();
                        $nombre_contacto = $customer->nombre_contacto;
                        $numdoc_contacto = $customer->numdoc_contacto;
                    }
                }

                $modelo_fact = DB::table('cat003')->where('id', $data->identificacion->tipoModelo)->first()->valor;
                $tipo_trans = DB::table('cat004')->where('id', $data->identificacion->tipoOperacion)->first()->valor;
                
                if(isset($data->emisor->tipoEstablecimiento)) {
                    $tipo_establec = DB::table('cat009')->where('id', $data->emisor->tipoEstablecimiento)->first()->valor;
                }
                
                if(isset($data->resumen->condicionOperacion)) {
                    $cond_opera = DB::table('cat016')->where('id', $data->resumen->condicionOperacion)->first()->valor;
                }

                if($request->input('recintoFiscal') != '') {
                    $rec_fiscal = DB::table('cat027')->where('id', $request->input('recintoFiscal'))->first()->valor;
                }

                if($request->input('regimen') != '') {
                    $regimen = DB::table('cat028')->where('id', $request->input('regimen'))->first()->valor;
                }

                if(isset($data->receptor->nit)){
                    $customer = Customer::where('nit',$data->receptor->nit)->first();
                    $nombre_contacto = $customer->nombre_contacto;
                    $numdoc_contacto = $customer->numdoc_contacto;
                } 


                //dd($numdoc_contacto);

                //dd($dir_emi);
                $view = View::make($pdf_template, compact(
                    'data',
                    'dir_emi',
                    'dir_rec',
                    'tipo_doc',
                    'modelo_fact',
                    'tipo_trans',
                    'tipo_establec',
                    'cond_opera',
                    'rec_fiscal',
                    'regimen',
                    'nombre_contacto',
                    'numdoc_contacto'
                    ))->render();
                $dompdf = new Dompdf();
                $dompdf->loadHtml($view);
                $dompdf->render();

                //$filename = $data->identificacion->codigoGeneracion.'.pdf';
                $filename = session()->getId().".pdf";
                $this->ensureStorageDirectory('sessions');
                file_put_contents(storage_path('app/sessions/'.$filename), $dompdf->output());

                $filenameOriginal = $request->file('file')->getClientOriginalName();

                // Dependiendo del tipo se pasa si es Receptor, Sujeto Excluido, Donante, Sujeto de Retencion o Afiliado
                switch($request->type) {
                    case '07': // 07 - Comprobante de Retencion (Sujeto de Retencion)
                        $tmp_json_decode = json_decode($json, true);
                        $tmp_json_decode['sujetoRetencion'] = $tmp_json_decode['receptor'];
                        unset(
                            $tmp_json_decode['receptor']
                        );
                        $json = json_encode($tmp_json_decode, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                        break;
                    case '14': // 14 - Factura de Sujeto Excluido (Sujeto Excluido)
                        $tmp_json_decode = json_decode($json, true);
                        $tmp_json_decode['sujetoExcluido'] = $tmp_json_decode['receptor'];
                        unset(
                            $tmp_json_decode['receptor']
                        );
                        $json = json_encode($tmp_json_decode, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                        break;
                }

                //dd($json);

                $dte = $this->guardarDTE($json, $filenameOriginal, $issuer['provider'], $issuer['nit']);
                
                $providerName = $issuer['provider'] === 'infile' ? 'Infile' : 'RRD';
                $confirm = 'PDF Generado Satisfactoriamente para el archivo '.$filenameOriginal." Desea enviar el documento a ".$providerName."?";
                
                $dteId = $dte->id;

                $filename = $json_decode->identificacion->codigoGeneracion.'.json';
                $jsonToStore = $json;

                if ($issuer['provider'] === 'infile') {
                    $jsonToStore = json_encode((new InfileSimplifiedDteBuilder())->build(json_decode($json)), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                }

                $this->ensureStorageDirectory('json');
                file_put_contents(storage_path('app/json/'.$filename), $jsonToStore);
                
                //return redirect()->route('upload', $dteId)->with('confirm', $message);
                return redirect('/billing')->with(compact('confirm','dteId'));
                //return response()->download(storage_path('app/'.$filename));
                //return back()->with('success', 'Archivo PDF generado exitosamente');
                // Guardar el JSON en un archivo
                
                //return response()->download(storage_path('app/'.$filename))->deleteFileAfterSend(true);
            } else {
                $errors = [];
                foreach($validator->getErrors() as $error) {
                    $errors[] = "Error en '{$error['property']}': {$error['message']}";
                }

                // return response()->json(['errors' => $errors], 400);
                return redirect('/billing')->with(compact('errors'));
            }
        } catch(Exception $e) {
            DocumentBase::consumeLocalCorrelatives(true);
            $errors[] = $e->getMessage();
            //dd($errors);
            return redirect('/billing')->with(compact('errors'));
        }
    }

    private function guardarDTE($json, $file_csv, $provider = 'rrd', $emisorNit = null) 
    {
        $json_decode = json_decode($json);

        switch($json_decode->identificacion->tipoDte){
            case '07':
                $customer = Customer::where('nit', isset($json_decode->sujetoRetencion->numDocumento) ? $json_decode->sujetoRetencion->numDocumento : $json_decode->sujetoRetencion->nit)->first();
                break;
            case '14':
                $customer = Customer::where('nit', isset($json_decode->sujetoExcluido->numDocumento) ? $json_decode->sujetoExcluido->numDocumento : $json_decode->sujetoExcluido->nit)->first();
                break;
            default:
                $customer = Customer::where('nit', isset($json_decode->receptor->numDocumento) ? $json_decode->receptor->numDocumento : $json_decode->receptor->nit)->first();
                break;
        }

        $dte = dte::create([
            'customer_id' => $customer->id,
            'numeroControl' => $json_decode->identificacion->numeroControl,
            'codigoGeneracion' => $json_decode->identificacion->codigoGeneracion,
            'file_csv' => $file_csv,
            'json_dte' => $json,
            'created_by' => auth()->user()->id,
            'tipoDte' => $json_decode->identificacion->tipoDte,
            'provider' => $provider,
            'emisor_nit' => $emisorNit,
        ]);

        return $dte;
    } 

    private function resolveIssuer($company)
    {
        $issuers = [
            'enerwire' => [
                'nit' => '06140411881014',
                'provider' => 'rrd',
                'env_prefix' => 'DTE_EMISOR',
            ],
            'onewire' => [
                'nit' => '05011011221017',
                'provider' => 'infile',
                'env_prefix' => 'ONEWIRE_DTE_EMISOR',
            ],
        ];

        if (!isset($issuers[$company])) {
            throw new Exception('La empresa seleccionada no es valida.');
        }

        return $issuers[$company];
    }

    private function applyIssuerToJson($json, array $issuer)
    {
        if ($issuer['provider'] !== 'infile') {
            return $json;
        }

        $data = json_decode($json, true);
        $prefix = $issuer['env_prefix'];
        $this->validateIssuerConfig($prefix);

        $data['emisor']['nit'] = env($prefix.'_NIT', $issuer['nit']);
        $data['emisor']['nrc'] = env($prefix.'_NRC', data_get($data, 'emisor.nrc'));
        $data['emisor']['nombre'] = env($prefix.'_NOMBRE', data_get($data, 'emisor.nombre'));
        $data['emisor']['codActividad'] = env($prefix.'_CODACTIVIDAD', data_get($data, 'emisor.codActividad'));
        $data['emisor']['descActividad'] = env($prefix.'_DESCACTIVIDAD', data_get($data, 'emisor.descActividad'));
        $data['emisor']['nombreComercial'] = env($prefix.'_NOMBRECOMERCIAL', data_get($data, 'emisor.nombreComercial'));
        $data['emisor']['tipoEstablecimiento'] = env($prefix.'_TIPOESTABLECIMIENTO', data_get($data, 'emisor.tipoEstablecimiento'));
        $data['emisor']['direccion']['departamento'] = env($prefix.'_DIRECCION_DEPARTAMENTO', data_get($data, 'emisor.direccion.departamento'));
        $data['emisor']['direccion']['municipio'] = env($prefix.'_DIRECCION_MUNICIPIO', data_get($data, 'emisor.direccion.municipio'));
        $data['emisor']['direccion']['complemento'] = env($prefix.'_DIRECCION_COMPLEMENTO', data_get($data, 'emisor.direccion.complemento'));
        $data['emisor']['telefono'] = env($prefix.'_TELEFONO', data_get($data, 'emisor.telefono'));
        $data['emisor']['correo'] = env($prefix.'_EMAIL', data_get($data, 'emisor.correo'));
        $data['emisor']['codEstableMH'] = env($prefix.'_CODESTABLEMH', data_get($data, 'emisor.codEstableMH'));
        $data['emisor']['codEstable'] = env($prefix.'_CODESTABLE', data_get($data, 'emisor.codEstable'));
        $data['emisor']['codPuntoVentaMH'] = env($prefix.'_CODPUNTOVENTAMH', data_get($data, 'emisor.codPuntoVentaMH'));
        $data['emisor']['codPuntoVenta'] = env($prefix.'_CODPUNTOVENTA', data_get($data, 'emisor.codPuntoVenta'));

        $data['identificacion']['numeroControl'] = 'DTE-'.$data['identificacion']['tipoDte'].'-'.$data['emisor']['codEstable'].$data['emisor']['codPuntoVenta'].'-'.substr($data['identificacion']['numeroControl'], -15);

        return json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    private function validateIssuerConfig($prefix)
    {
        $required = [
            'NIT',
            'NRC',
            'NOMBRE',
            'CODACTIVIDAD',
            'DESCACTIVIDAD',
            'NOMBRECOMERCIAL',
            'TIPOESTABLECIMIENTO',
            'DIRECCION_DEPARTAMENTO',
            'DIRECCION_MUNICIPIO',
            'DIRECCION_COMPLEMENTO',
            'TELEFONO',
            'EMAIL',
            'CODESTABLE',
            'CODPUNTOVENTA',
        ];

        $missing = [];

        foreach ($required as $key) {
            if (env($prefix.'_'.$key) === null || env($prefix.'_'.$key) === '') {
                $missing[] = $prefix.'_'.$key;
            }
        }

        if ($missing) {
            throw new Exception('Faltan variables de emisor para OneWire: '.implode(', ', $missing));
        }
    }

    private function ensureStorageDirectory($directory)
    {
        $path = storage_path('app/'.$directory);

        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    private function processExcel($file, $type, $request)
    {
        $file_content = file_get_contents($file);
        $string = mb_convert_encoding($file_content, 'UTF-8', "ISO-8859-1");
        
        $collection = $this->parse_csv($string);

        // Lógica para procesar el archivo Excel y obtener los datos
        // Aquí puedes utilizar una librería como "maatwebsite/excel" para leer el archivo y obtener los datos
        // $collection = Excel::toCollection(null, $file)[0];

        //dd($collection);

        /*$reader = $collection->map(function($row) {
            return array_map(function($value) {
            return str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], 
                                   ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'], 
                                   $value);
            }, $row->toArray());
        });*/
        
        $reader = $collection;

        $encabezados = $reader[0];

        $nit_xls = (trim(str_replace("-","",$reader[2][2])) == '') ? trim(str_replace("-","",$reader[3][2])) : trim(str_replace("-","",$reader[2][2]));
        $nrc_xls = (trim(str_replace("-","",$reader[2][3])) == '') ? trim(str_replace("-","",$reader[3][3])) : trim(str_replace("-","",$reader[2][3]));

        //dd($nit_xls);

        if($nit_xls == '') {
            if($nrc_xls == '') {
                throw new Exception("No hay registrado ningun Cliente con el NIT(".$nit_xls.") ni con el NRC(".$nrc_xls.")");
            }
        }

        $receptor = Customer::where('nit', $nit_xls)->orWhere('nrc', $nrc_xls)->first();

        //dd($receptor);

        $datos = [];

        for($i=2; $i < count($reader); $i++) {
            $linea = (array) $reader[$i];

            if (in_array('Total '.$reader[1][0], $linea)) {
                $encontradoTotal = true;
                break;
            }

            $datosLinea = [];

            foreach($linea as $indice => $valor) {
                $clave = $encabezados[$indice];
                $datosLinea[$clave] = $valor;
            }

            $datos[] = $datosLinea;
        }

        //dd($datos);

        if(isset($receptor->codActividad)){
            $descActividad = DB::table('cat019')
                    ->where('id', $receptor->codActividad)
                    ->value('valor');
        }

        if($receptor) {
            $datosReceptor = [
                'nit' => $receptor->nit,
                'nrc' => $receptor->nrc,
                'nombre' => $receptor->nombre,
                'codActividad' => $receptor->codActividad,
                'descActividad' => $descActividad,
                'nombreComercial' => $receptor->nombreComercial,
                'departamento' => $receptor->departamento,
                'municipio' => $receptor->municipio,
                'complemento' => $receptor->complemento,
                'codPais' => $receptor->codPais,
                'codDomiciliado' => $receptor->codDomiciliado,
                'codigoMH' => $receptor->codigoMH,
                'puntoVentaMH' => $receptor->puntVentaMH,
                'bienTitulo' => $receptor->bienTitulo,
                'tipoPersona' => $receptor->tipoPersona,
                'telefono' => $receptor->telefono,
                'correo' => $receptor->correo,
                'category_id' => $receptor->category_id,
                'nombre_contacto' => $receptor->nombre_contacto,
                'tipodoc_contacto' => $receptor->tipodoc_contacto,
                'numdoc_contacto' => $receptor->numdoc_contacto,
            ];
        } else {
            $datosReceptor = [];
            throw new Exception("No hay registrado ningun Cliente con el NIT(".$nit_xls.") ni con el NRC(".$nrc_xls.")");
        }   
        
        //dd($datos);

        $detalleItems = [];
        $detalleResumen = [];
        $terms = [];

        $total = 0;

        for($i=0; $i < count($datos); $i++) {
            if($datos[$i]["Type"] != "") {
                if(isset($datos[$i]["U/M"])) {
                    if($datos[$i]["U/M"] == null || $datos[$i]["U/M"] == 'ea') {
                        $unidad = 'NG';
                    } else {
                        $qb_um = DB::table('qb_um')
                            ->where('um', $datos[$i]["U/M"])
                            ->first();
                    
                        if($qb_um) {
                            $unidad = $qb_um->codigo_mh;
                        } else {
                            $unidad = "99";
                        }
                    }
                }else{
                    $unidad = "99";
                }

                if(is_numeric($datos[$i]["Amount"])) {
                    $monto = (float) $datos[$i]["Amount"];
                } else if(is_numeric($datos[$i]["Credit"])) {
                    $monto = (float) $datos[$i]["Credit"];
                } else {
                    $monto = 0;
                }

                if(isset($datos[$i]["Sales Price"])) {
                    $precio = $datos[$i]["Sales Price"];
                } else {
                    if(isset($datos[$i]["Cost Price"])){
                        $precio = $datos[$i]["Cost Price"];
                    } else {
                        if(isset($datos[$i]["Amount"])) {
                            $precio = $datos[$i]["Amount"];
                        } else {
                            $precio = 0;
                        }
                    } 
                }

                $item = isset($datos[$i]["Memo"]) ? $datos[$i]["Memo"] : $datos[$i]["Item"];
                $item .= isset($datos[$i]["COLOR"]) ? " ".$datos[$i]["COLOR"] : "";

                $descripcion = isset($datos[$i]["Memo"]) ? $datos[$i]["Memo"] : $datos[$i]["Item Description"];
                $descripcion .= isset($datos[$i]["COLOR"]) ? " ".$datos[$i]["COLOR"] : "";

                $detalleItem = [
                    //'item' => isset($datos[$i]["Memo"]) ? $datos[$i]["Memo"] : $datos[$i]["Item"],
                    'item' => $item,
                    //'descripcion' => isset($datos[$i]["Memo"]) ? $datos[$i]["Memo"] : $datos[$i]["Item Description"],
                    'descripcion' => $descripcion,
                    'cantidad' => isset($datos[$i]["Qty"]) ? $datos[$i]["Qty"] : null,
                    'unidad' => $unidad,
                    'precio' => $precio,
                    'monto' => $monto,
                    'numdoc' => $datos[$i]["Num"],
                    'date' => $datos[$i]["Date"],
                    'due_date' => $datos[$i]["Due Date"],
                ];

                $detalleItems[] = $detalleItem;

                $total = $total + $monto;

                $docsRelacionados[] = [
                    'numdoc' => $datos[$i]["Num"],
                    'date' => $datos[$i]["Date"],
                    'due_date' => $datos[$i]["Due Date"], 
                ];

                $terms[] = [
                    'terms' => $datos[$i]["Terms"],
                ];
            }
        }
        
        $documentoRelacionado = array_unique($docsRelacionados, SORT_REGULAR);
        $condicion = array_unique($terms, SORT_REGULAR);

        $detalleResumen = [
            'monto' => $total,
            'documentoRelacionado' => $documentoRelacionado,
            'condicion' => $condicion[0]['terms'],
        ];

        $observaciones = $request->comments;

        //dd($detalleItems);

        // Ejemplo de generación de objetos de documento según el tipo
        switch ($type) {
            // FCE - Factura Electrónica
            case '01':
                $documento = new FacturaElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                $data['extension']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // CCFE - Comprobante de Credito Fiscal Electrónico
            case '03':
                $documento = new ComprobanteCreditoFiscalElectronico($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                $data['extension']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // NRE - Nota de Remision Electrónica   
            case '04':
                $documento = new NotaRemisionElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                unset(
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // NCE - Nota de Credito Electrónica
            case '05':
                $documento = new NotaCreditoElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                unset(
                    $data['emisor']['codEstableMH'],
                    $data['emisor']['codEstable'],
                    $data['emisor']['codPuntoVentaMH'],
                    $data['emisor']['codPuntoVenta'],
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // NDE - Nota de Debito Electrónica
            case '06':
                $documento = new NotaDebitoElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                unset(
                    $data['emisor']['codEstableMH'],
                    $data['emisor']['codEstable'],
                    $data['emisor']['codPuntoVentaMH'],
                    $data['emisor']['codPuntoVenta'],
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // CRE - Comprobante de Retención Electrónico
            case '07':
                $documento = new ComprobanteRetencionElectronico($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                unset(
                    $data['emisor']['codEstableMH'],
                    $data['emisor']['codEstable'],
                    $data['emisor']['codPuntoVentaMH'],
                    $data['emisor']['codPuntoVenta'],
                    $data['documentoRelacionado'],
                    $data['ventaTercero'],
                    $data['otrosDocumentos']
                );
                $data['extension']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // FEXE - Factura de Exportación Electrónica
            case '11':
                $recintoFiscal = $request->input('recintoFiscal');
                $regimen = $request->input('regimen');

                $datosEmisor = [
                    'recintoFiscal' => $recintoFiscal,
                    'regimen' => $regimen,
                ];

                //dd($datosEmisor);

                if(in_array('INCOTERMS', $encabezados)){
                    $codIncoterms = $reader[2][17];
                    
                    $incoterms = DB::table('cat031')->where('codigo', $codIncoterms)->first();
                    //dd($incoterms);

                    $detalleResumen += [
                        'codIncoterms' => $incoterms->id,
                        'descIncoterms' => $incoterms->valor
                    ];
                }

                $documento = new FacturaExportacionElectronica($datosReceptor, $detalleItems, $detalleResumen, $datosEmisor);
                $data = $documento->toArray();
                unset($data['documentoRelacionado'], $data['extension']);
                $data['resumen']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // FSEE - Factura de Sujeto Excluido Electrónica
            case '14':
                $documento = new FacturaSujetoExcluidoElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                unset(
                    $data['emisor']['nombreComercial'],
                    $data['emisor']['tipoEstablecimiento'],
                    $data['documentoRelacionado'],
                    $data['otrosDocumentos'],
                    $data['ventaTercero'],
                    $data['extension']
                );
                $data['resumen']['observaciones'] = $observaciones;
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            default:
                // Manejo de error si el tipo de documento no es válido
                break;
        }

        //dd($data);
        //return $this->generarJson($type, $json);
        return $json;
    }

    public function getCustomerData(Request $request) {
        $nit = $request->input('nit');

        $customer = Customer::where('nit', $nit)->first();

        if($customer) {
            $departamento = DB::table('cat012')
                ->where('id', $customer->departamento)
                ->value('valor');

            $municipio = DB::table('cat013')
                ->where('id', $customer->municipio)
                ->where('departamento', $customer->departamento)
                ->value('valor');

            return response()->json([
                'nrc' => $customer->nrc,
                'nombre' => $customer->nombre,
                'departamento' => $departamento,
                'municipio' => $municipio,
                'direccion' => $customer->complemento,
                'nombre_contacto' => $customer->nombre_contacto,
                'numdoc_contacto' => $customer->numdoc_contacto,
            ]);
        } else {
            return response()->json([
                'error' => 'Cliente no encontrado'
            ], 404);
        }
    }

    private function is_json_string($json_str)
    {
        json_decode($json_str);
        return json_last_error() === JSON_ERROR_NONE;
    }

    function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
        return array_map(
            function ($line) use ($delimiter, $trim_fields) {
                return array_map(
                    function ($field) {
                        return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                    },
                    $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line)
                );
            },
            preg_split(
                $skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s',
                preg_replace_callback(
                    '/"(.*?)"/s',
                    function ($field) {
                        return urlencode(utf8_encode($field[1]));
                    },
                    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string)
                )
            )
        );
    }
}
