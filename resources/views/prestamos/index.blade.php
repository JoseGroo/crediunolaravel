@extends('layouts.layout')

@section("title", "Cobranza")

@section('content')
    {{ Form::open([ 'route' => ['prestamos.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">
                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Sucursal</label>
                            {{ Form::select('sucursal_id', $sucursales, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Ciudad</label>
                            {{ Form::select('ciudad_id', $ciudades, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
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
                            <label class="font-normal">Cliente</label>
                            {{ Form::select('cliente_id', [], null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion', 'id' => 'cliente_id' ]) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Grupo</label>
                            {{ Form::select('grupo_id', $grupos, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Tipo de prestamo</label>
                            {{ Form::select('interes_id', $intereses, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="checkbox checkbox-success" style="padding-top: 15px;">
                            <input type="checkbox" name="mostrar_liquidados" id="mostrar_liquidados" class="check-box-value" value="1">
                            {{ Form::label('mostrar_liquidados', 'Mostrar abonos liquidados') }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-secondary" style="width: 160px;" type="button">Descargar recibos</button>
                        {{ Form::select('tipo_reporte', [1 => 'Cobranza', 2 => 'Ruta', 3 => 'Cobranza de grupos'], null, ['class' => 'form-control mt-1', 'style' => 'width: 160px;']) }}
                    </div>
                    <div class="col-md-6 col-sm-6 col-12 text-right">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue" type="submit">Buscar</button>
                        <a href="{{ route('prestamos.index') }}" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("prestamos._index")

            </div>
        </div>
    {{ Form::close() }}

    <div class="modal inmodal" id="modalCarta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 1000px;">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="titleCarta">Datos de</h4>
                </div>
                {{ Form::open([ 'route' => ['clientes.certificado_patrimonial_pdf' ], 'method' => 'POST', 'id' => 'frmDescargarCarta' ]) }}
                {{ Form::hidden('cliente_id') }}
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="pretty p-icon p-curve mr-0">
                                    <input type="checkbox" name="agregar_foto" value="1" />
                                    <div class="state">
                                        <i class="icon mdi mdi-check"></i>
                                        <label>Agregar foto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('calle', __('validation.attributes.calle')) }}
                                {{ Form::text('calle', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('numero_exterior', __('validation.attributes.numero_exterior')) }}
                                {{ Form::text('numero_exterior', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('colonia', __('validation.attributes.colonia')) }}
                                {{ Form::text('colonia', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('codigo_postal', __('validation.attributes.codigo_postal')) }}
                                {{ Form::text('codigo_postal', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="pretty p-icon p-curve mr-0">
                                    <input type="checkbox" checked name="mostrar_telefono" value="1" />
                                    <div class="state">
                                        <i class="icon mdi mdi-check"></i>
                                        <label>Mostrar telefono</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('telefonos', __('validation.attributes.telefono')) }}
                                {{ Form::text('telefonos', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                    </div>

                    <hr class="form-row">

                    <div class="form-row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="pretty p-icon p-curve mr-0">
                                    <input type="checkbox" checked name="mostrar_direccion_sucursal" value="1" />
                                    <div class="state">
                                        <i class="icon mdi mdi-check"></i>
                                        <label>Mostrar direccion sucursal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                {{ Form::label('direccion_sucursal', 'Direccion sucursal') }}
                                {{ Form::textarea('direccion_sucursal', null, [ 'class' => 'form-control', 'rows' => 3 ]) }}
                            </div>
                        </div>
                    </div>
                    <hr class="form-row">
                    <div class="form-row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('saldo_total', __('validation.attributes.saldo_total')) }}
                                {{ Form::text('saldo_total', null, [ 'class' => 'form-control just-decimal' ]) }}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('pagos_vencidos', __('validation.attributes.pagos_vencidos')) }}
                                {{ Form::text('pagos_vencidos', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                {{ Form::label('argumento', __('validation.attributes.argumento')) }}
                                {{ Form::textarea('argumento', null, [ 'class' => 'form-control', 'rows' => 3 ]) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Descargar</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection



@section("scripts")
    @parent
    <script>
        $(document).on('click', '#selectAll', function () {
            var vChecked = $(this).prop('checked');
            $('#tableCobranza input[type="checkbox"]').prop('checked', vChecked);
        })
        $(document).on('click', '[data-target="#modalCarta"]', function () {
            $('#frmDescargarCarta').trigger("reset");
            var vClienteId = $(this).data('cliente-id');
            var route = $(this).data('route');
            var titleModal = 'Datos de ' + $(this).text().toLowerCase();

            $('#titleCarta').text(titleModal);
            $('#frmDescargarCarta').prop('action', route);
            $('#modalCarta [name="cliente_id"]').val(vClienteId);
        })

        $(document).ajaxComplete(function(event, jqxhr, settings) {

        })

        $(function (){
            $('#cliente_id').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                language: 'es',
                width:'100%',
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function (data) {
                    return data.html;
                },
                templateSelection: function (data) {
                    return data.text;
                },
                minimumInputLength: 1,
                placeholder: 'Busca un cliente',
                ajax: {
                    url: '{{ route('clientes.autocomplete_cliente_html') }}',
                    dataType: 'json',
                    method: 'POST',
                    delay: 250,

                    data: function (params) {
                        return {
                            term: params.term,
                            _token: "{{ csrf_token() }}"
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                }
            });
        })
    </script>
@endsection





