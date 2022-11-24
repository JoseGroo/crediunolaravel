@extends('layouts.layout')

@section("title", "Listado de cortes")

@section('create_button')
<!--    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('dias_festivos.create') }}">Crear nuevo día festivo</a>-->
@endsection

@section('content')
    {{ Form::open([ 'route' => ['cortes.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label class="font-normal">Rango de fechas</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="form-control datepicker" readonly name="fecha_inicio" data-placeholder="Desde">
                                <span class="input-group-addon" style="padding-top: 12px;">a</span>
                                <input type="text" class="form-control datepicker" readonly name="fecha_fin" data-placeholder="Hasta">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Sucursal</label>
                            {{ Form::select('sucursal_id', $sucursales, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label>Estatus</label>

                            <div>
                                <div class="radio radio-success inline">
                                    <input type="radio" checked name="estatus" id="estatus_1" class="check-box-value" value="" placeholder="">
                                    <label for="estatus_1">Todos</label>
                                </div>

                                <div class="radio radio-success inline">
                                    <input type="radio" name="estatus" id="estatus_2" class="check-box-value" value="2" placeholder="">
                                    <label for="estatus_2">Abiertos</label>
                                </div>

                                <div class="radio radio-success inline">
                                    <input type="radio" name="estatus" id="estatus_3" class="check-box-value" value="1" placeholder="">
                                    <label for="estatus_3">Cerrados</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue" type="submit">Buscar</button>
                        <a href="#" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("cortes._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>

    </script>
@endsection
