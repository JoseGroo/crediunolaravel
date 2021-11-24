@extends('layouts.layout')

@section("title", "Listado de formas de pago")

@section('create_button')

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
                       <ul class="nav nav-tabs nav-fill" id="tabFormasPago" role="tablist">

                           <li class="nav-item">
                               <a class="nav-link active" data-toggle="tab" href="#tab-cheque" role="tab" aria-controls="tab-cheque" aria-selected="true">Cheque</a>
                           </li>

                           <li class="nav-item">
                               <a class="nav-link" data-toggle="tab" href="#tab-tarjeta" role="tab" aria-controls="tab-tarjeta" aria-selected="true">Tarjeta de crédito/debito</a>
                           </li>

                           <li class="nav-item">
                               <a class="nav-link" data-toggle="tab" href="#tab-ficha-deposito" role="tab" aria-controls="tab-ficha-deposito" aria-selected="true">Ficha de deposito</a>
                           </li>

                           <li class="nav-item">
                               <a class="nav-link" data-toggle="tab" href="#tab-transferencia-electronica" role="tab" aria-controls="tab-transferencia-electronica" aria-selected="true">Transferencia electronica</a>
                           </li>

                       </ul>

                       <div class="tab-content">
                           <div id="tab-cheque" class="tab-pane show active">
                               @include('formas_pago._cheques', [ 'model' => $cheques])
                           </div>
                           <div id="tab-tarjeta" class="tab-pane">
                               @include('formas_pago._tarjeta', [ 'model' => $tarjetas])
                           </div>
                           <div id="tab-ficha-deposito" class="tab-pane">
                               @include('formas_pago._ficha_deposito', [ 'model' => $ficha_deposito])
                           </div>
                           <div id="tab-transferencia-electronica" class="tab-pane">
                               @include('formas_pago._transferencia_electronica', [ 'model' => $transferencias_electronicas])
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
