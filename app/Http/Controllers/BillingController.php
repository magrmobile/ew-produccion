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
use App\Documents\NotaCreditoElectronica;
use App\Documents\NotaDebitoElectronica;
use App\Documents\NotaRemisionElectronica;
use App\Documents\ComprobanteCreditoFiscalElectronico;
use App\Documents\ComprobanteRetencionElectronico;

use JsonSchema\Validator;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

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
            'file' => 'required|mimetypes:text/csv,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'type' => 'required|in:01,03,04,05,06,07,11,14'
        ]);

        $file = $request->file('file');

        // Procesar el archivo Excel y generar el JSON correspondiente
        try{
            $json = $this->processExcel($file, $request->input('type'), $request);
 
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

            //dd(json_decode($json));

            if($validator->isValid()) {
                $data = json_decode($json);

                //dd($data);

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
                    'regimen'
                    ))->render();
                $dompdf = new Dompdf();
                $dompdf->loadHtml($view);
                $dompdf->render();

                //$filename = $data->identificacion->codigoGeneracion.'.pdf';
                $filename = session()->getId().".pdf";
                file_put_contents(storage_path('app/'.$filename), $dompdf->output());

                $filenameOriginal = $request->file('file')->getClientOriginalName();

                $this->guardarDTE($json, $filenameOriginal);
                
                $message = 'PDF Generado Satisfactoriamente para el archivo '.$filenameOriginal;

                $filename = $json_decode->identificacion->codigoGeneracion.'.json';
                file_put_contents(storage_path('app/'.$filename), $json);
                
                

                return redirect('/billing')->with(compact('message'));
                //return response()->download(storage_path('app/'.$filename));
                //return back()->with('success', 'Archivo PDF generado exitosamente');
                // Guardar el JSON en un archivo
                
                //return response()->download(storage_path('app/'.$filename))->deleteFileAfterSend(true);
            } else {
                $errors = [];
                foreach($validator->getErrors() as $error) {
                    $errors[] = "Error en '{$error['property']}': {$error['message']}";
                }

                //$notification = 
                return response()->json(['errors' => $errors], 400);
            }
        } catch(Exception $e) {
            $notification = $e->getMessage();
            return redirect('/billing')->with(compact('notification'));
        }
    }

    private function guardarDTE($json, $file_csv) 
    {
        $json_decode = json_decode($json);

        $customer = Customer::where('nit', isset($json_decode->receptor->numDocumento) ? $json_decode->receptor->numDocumento : $json_decode->receptor->nit)->first();

        dte::create([
            'customer_id' => $customer->id,
            'numeroControl' => $json_decode->identificacion->numeroControl,
            'codigoGeneracion' => $json_decode->identificacion->codigoGeneracion,
            'file_csv' => $file_csv,
            'json_dte' => $json,
            'created_by' => auth()->user()->id,
        ]);
    } 

    private function processExcel($file, $type, $request)
    {
        // Lógica para procesar el archivo Excel y obtener los datos
        // Aquí puedes utilizar una librería como "maatwebsite/excel" para leer el archivo y obtener los datos
        $collection = Excel::toCollection(null, $file)[0];

        $reader = $collection->map(function($row) {
            return array_map(function($value) {
            return str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], 
                                   ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'], 
                                   $value);
            }, $row->toArray());
        });
        
        $encabezados = $reader[0];

        $nit_xls = trim(str_replace("-","",$reader[2][2]));
        $nrc_xls = trim(str_replace("-","",$reader[2][3]));

        if($nit_xls == '') {
            if($nrc_xls == '') {
                throw new Exception("No hay registrado ningun Cliente con el NIT(".$nit_xls.") ni con el NRC(".$nrc_xls.")");
            }
        }

        $receptor = Customer::where('nit', $nit_xls)->orWhere('nrc', $nrc_xls)->first();

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
                'category_id' => $receptor->category_id
            ];
        } else {
            $datosReceptor = [];
        }   
        
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

                $detalleItem = [
                    'item' => isset($datos[$i]["Item"]) ? $datos[$i]["Item"] : $datos[$i]["Memo"],
                    'descripcion' => isset($datos[$i]["Item Description"]) ? $datos[$i]["Item Description"] : $datos[$i]["Memo"],
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

        //dd($detalleItems);

        // Ejemplo de generación de objetos de documento según el tipo
        switch ($type) {
            // FCE - Factura Electrónica
            case '01':
                $documento = new FacturaElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // CCFE - Comprobante de Credito Fiscal Electrónico
            case '03':
                $documento = new ComprobanteCreditoFiscalElectronico($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                break;
            // NRE - Nota de Remision Electrónica   
            case '04':
                $documento = new NotaRemisionElectronica($datosReceptor, $detalleItems, $detalleResumen);
                $data = $documento->toArray();
                unset(
                    $data['otrosDocumentos']
                );
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
                'direccion' => $customer->complemento
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
}


