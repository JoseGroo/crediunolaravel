@extends('layouts.layout')

@section("title", "Listado de formas de pago")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('fondos.create') }}">Crear nuevo fondo</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['fondos.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Formas de pago</h5>

            </div>


            <div class="card-body">
                <div class="form-row">

                   <div class="col-md-12">
                       <ul class="nav nav-tabs" id="tabFormasPago" role="tablist">

                           @foreach($formas_pago as $item)
                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#tab-{{ $item->value }}" role="tab" aria-controls="tab-{{ $item->value }}" aria-selected="true">{{ $item->description }}</a>
                               </li>
                           @endforeach

                       </ul>

                       <div class="tab-content">
                           <div id="tab-documentos" class="tab-pane">
                               kp2
                           </div>
                           <div id="tab-documentos" class="tab-pane">
                               kp2
                           </div>
                       </div>
                   </div>

                </div>
            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vFondoId = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            var vAccion = $(this).text().trim();

            var vTextSwal = vAccion === 'Desactivar' ? 'El registro quedara desactivado' : 'El registro se reactivara';

            vFondoId = $(this).attr("fondo-id");

            Swal.fire({
                title: '¿Desea continuar?',
                text: vTextSwal,
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
                        url: "{{route('fondos.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            fondo_id: vFondoId,
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
    </script>
@endsection