@extends('layouts.layout')

@section("title", "Listado de avales")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('avales.create') }}">Crear nuevo aval</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['avales.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-4 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" autocomplete="off" name="nombre" class="form-control" value="{{ $nombre }}" />
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Domicilio</label>
                            <input type="text" autocomplete="off" name="domicilio" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Sucursal</label>
                            {{ Form::select('sucursal_id', $sucursales, $sucursal_id, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-3 col-12 text-right">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue filtrar" type="submit">Buscar</button>
                        <a href="{{ route('avales.index') }}" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("avales._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        $(document).on('click', '.hacerlo-cliente', function(event){
            event.preventDefault();
            var vAvalId = $(this).data('id');

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El aval se va a hacer cliente.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Convirtiendo aval a cliente...");

                    $.ajax({
                        url: "{{route('avales.hacer_cliente')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            aval_id: vAvalId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {

                            var vClass = data.Saved ? "success" : "error";

                            if(data.Saved)
                            {
                                $('#frmIndex').submit();
                            }
                            MyToast("Notificación", data.Message, vClass);
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })
        })
    </script>
@endsection