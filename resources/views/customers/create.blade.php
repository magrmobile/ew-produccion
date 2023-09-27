@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-1">
        <div class="row align-items-center">
        <div class="col">
            <h2 class="mb-0">Nuevo Cliente</h2>
        </div>
        <div class="col text-right">
            <a href="{{ url('customers') }}" class="btn btn-sm btn-default">
                Cancelar y volver
            </a>
        </div>
        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ url('customers') }}" method="post">
            @csrf
            <div>
                <!-- NIT y NCR -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="nit">NIT</label>
                            <input id="nit" name="nit" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="nrc">NRC</label>
                            <input id="nrc" name="nrc" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- Nombre y Nombre Comercial -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>    
                            <input id="nombre" name="nombre" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="nombreComercial">Nombre Comercial</label>
                            <input id="nombreComercial" name="nombreComercial" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- Actividad Economica -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="codActividad">Codigo de Actividad</label>
                            <select name="codActividad" id="codActividad" class="form-control" required>
                                <option value="">Seleccionar Actividad Economica</option>
                                @foreach($codigos_actividad as $codActividad)
                                <option value="{{ $codActividad->id }}">{{ $codActividad->id.' - '.$codActividad->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Tipo Establecimiento, Departamento y Municipio -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="tipoEstablecimiento">Tipo de Establecmiento</label>
                            <select name="tipoEstablecimiento" id="tipoEstablecimiento" class="form-control">
                                <option value="">Seleccionar Tipo de Establecimiento</option>
                                @foreach($tipos_establecimiento as $tipoEstablecimiento)
                                <option value="{{ $tipoEstablecimiento->id }}">{{ $tipoEstablecimiento->id.' - '.$tipoEstablecimiento->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <select name="departamento" id="departamento" class="form-control">
                                <option value="">Seleccionar Departamento</option>
                                @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->id.' - '.$departamento->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <select name="municipio" id="municipio" class="form-control">
                                <option value="">Seleccionar Municipio</option>
                                @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}" data-departamento="{{ $municipio->departamento }}" >{{ $municipio->id.' - '.$municipio->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Complemento Direccion -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="complemento">Complemento Direccion</label>
                            <input id="complemento" name="complemento" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- Codigo de Pais, Codigo Domiciliado, Codigo Ministeio de Hacienda y Punto de Venta Ministerio de Hacienda -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="codPais">Codigo de Pais</label>
                            <select name="codPais" id="codPais" class="form-control">
                                <option value="">Seleccionar Codigo de Pais</option>
                                @foreach($codigos_pais as $codPais)
                                <option value="{{ $codPais->id }}">{{ $codPais->id.' - '.$codPais->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="codDomiciliado">Codigo Domiciliado</label>
                            <select name="codDomiciliado" id="codDomiciliado" class="form-control">
                                <option value="">Selecionar Codigo Domiciliado</option>
                                @foreach($codigos_domiciliado as $codDomiciliado)
                                <option value="{{ $codDomiciliado->id }}">{{ $codDomiciliado->id.' - '.$codDomiciliado->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="codigoMH">Codigo Ministerio de Hacienda</label>
                            <input id="codigoMH" name="codigoMH" type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="puntoVentaMH">Punto de Venta Ministerio de Hacienda</label>
                            <input id="puntoVentaMH" name="puntoVentaMH" type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                    </div>
                </div>
                <!-- Bien Titulo y Tipo de Persona -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="bienTitulo">Bien Titulo</label>
                            <select name="bienTitulo" id="bienTitulo" class="form-control">
                                <option value="">Seleccionar Bien Titulo</option>
                                @foreach($bienes_titulo as $bienTitulo)
                                <option value="{{ $bienTitulo->id }}">{{ $bienTitulo->id.' - '.$bienTitulo->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="tipoPersona">Tipo de Persona</label>
                            <select name="tipoPersona" id="tipoPersona" class="form-control">
                                <option value="">Seleccionar Tipo de Persona</option>
                                @foreach($tipos_persona as $tipoPersona)
                                <option value="{{ $tipoPersona->id }}">{{ $tipoPersona->id.' - '.$tipoPersona->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="category_id">Categoria del Cliente</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Seleccionar la Categoria del Cliente</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Telefono y Correo Electronico -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input id="telefono" name="telefono" type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="correo">Correo Electronico</label>
                            <input id="correo" name="correo" type="email" class="form-control">
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const departamentoSelect = document.getElementById('departamento');
        const municipioSelect = document.getElementById('municipio');

        departamentoSelect.addEventListener('change', function(){
            const selectDepartamento = departamentoSelect.value;
            for(const option of municipioSelect.options){
                if(option.getAttribute('data-departamento') === selectDepartamento || selectDepartamento === ''){
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
        });
    });
</script>
@endsection