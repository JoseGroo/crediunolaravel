@extends('layouts.layout')

@section("title", "Listado de medios publicitarios")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('medios-publicitarios.create') }}">Crear nuevo medio publicitario</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['medios-publicitarios.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Medio publicitario</label>
                            <input type="text" autocomplete="off" name="sMedioPublicitario" class="form-control" />
                        </div>
                    </div>


                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue filtrar" type="submit">Buscar</button>
                        <a href="#" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("medios_publicitarios._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vMedioPublicitarioId = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vMedioPublicitarioId = $(this).data("medio-publicitario-id");

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El registro quedara eliminado.',
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
                        url: "{{route('medios-publicitarios.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            medio_publicitario_id: vMedioPublicitarioId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {
                            if(data.Saved)
                                $("#frmIndex").submit();

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
    </script>
@endsection