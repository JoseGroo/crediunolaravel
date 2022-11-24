@extends('layouts.layout')

@section("title", "Listado de grupos")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('grupos-cliente.create') }}">Crear nuevo grupo</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['grupos-cliente.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Grupo</label>
                            <input type="text" autocomplete="off" name="sGrupo" class="form-control" />
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

                @include("grupos_cliente._index")

            </div>
        </div>
    {{ Form::close() }}

    <div class="modal inmodal" id="modalAddToGroup" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Agregar cliente a grupo <span id="sGrupo"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label('cliente_id', __('validation.attributes.cliente')) }}
                                {{ Form::select('cliente_id', [], null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion', 'autofocus' => true ]) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnAddCliente" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@endsection




@section("scripts")
    @parent

    <script>
        var vGrupoId = 0;


        $(document).on('click', '.add-client', function(event){
            event.preventDefault();

            vGrupoId = $(this).data("grupo-id");
            var vGrupo = $(this).data("grupo");
            $('#sGrupo').text(vGrupo);

            $('#modalAddToGroup').modal('show');
        })

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vGrupoId = $(this).data("grupo-id");

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El registro se va a eliminar.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Eliminando registro...");
                    $.ajax({
                        url: "{{route('grupos-cliente.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            grupo_id: vGrupoId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {
                            if(data.Saved)
                            {
                                $("#frmIndex").submit();
                            }
                            Swal.fire('Notificación', data.Message, data.Saved ? 'success' : 'error');

                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })
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

            $('#btnAddCliente').click(function(){

                var vClienteId = $('#cliente_id').val();

                if(vClienteId.length <= 0){
                    MyToast('Notificación', 'El cliente es obligatorio.', 'warning');
                    return;
                }

                ShowLoading("Agregando cliente...");
                $.ajax({
                    url: "{{route('grupos-cliente.add_cliente_to_group')}}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        grupo_id: vGrupoId,
                        cliente_id: vClienteId,
                        _token: "{{ csrf_token() }}"
                    },
                    cache: false,
                    success: function (data) {
                        if(data.Saved)
                        {
                            $("#frmIndex").submit();
                            $('#cliente_id').val('').trigger('change');
                            $('#modalAddToGroup').modal('hide');
                        }
                        Swal.fire('Notificación', data.Message, data.Saved ? 'success' : 'error');

                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })
        })
    </script>
@endsection
