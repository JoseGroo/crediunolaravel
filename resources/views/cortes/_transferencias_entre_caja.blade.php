
@if(!$corte->cerrado)
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" data-toggle="modal" data-target="#modalTransferenciaEntreCaja" class="btn btn-sm btn-info">Hacer transferencia</button>
        </div>
    </div>
@endif
<div class="table-responsive m-t-lg table-fix-head">

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Importe</th>
            <th>Divisa</th>
            <th colspan="2"></th>
        </tr>
        </thead>
        <tbody>
        @if($model && !$model->isEmpty())
            @foreach ($model as $item)
                @php($canceled = $item->estatus == \App\Enums\estatus_movimientos_corte::Cancelado ? "bg-danger text-white" : "")
                <tr class="{{ $canceled }}">
                    <td>{{ Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y h:i:s a') }}</td>
                    <td>
                        {{ $item->tbl_corte_origen->nombre_completo_usuario }}
                    </td>
                    <td>
                        {{ $item->tbl_corte_destino->nombre_completo_usuario }}
                    </td>
                    <td>
                        @money_format($item->importe)
                    </td>
                    <td>
                        {{ $item->tbl_divisa->divisa }}
                    </td>
                    <td>
                        @if($item->estatus == \App\Enums\estatus_movimientos_corte::Activo)
                            <a href="#" class="btn btn-sm btn-info print-transferencia-entre-caja" data-id="{{ $item->transferencia_entre_caja_id }}"><i class="mdi mdi-printer"></i> Imprirmir</a>
                        @endif
                    </td>
                    @if(!$corte->cerrado)
                        <td>
                            @if($item->estatus == \App\Enums\estatus_movimientos_corte::Activo)
                                <a href="#" class="btn btn-sm btn-danger cancel-transferencia-entre-caja" data-id="{{ $item->transferencia_entre_caja_id }}"><i class="mdi mdi-cancel"></i> Cancelar</a>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10">No hay movimientos registrados.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div class="modal inmodal" id="modalTransferenciaEntreCaja" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInUp">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Transferencia</h4>
            </div>
            {{ Form::open([ 'route' => ['cortes.hacer_transferencia_caja_post' ], 'method' => 'POST', 'id' => 'frmTransferenciaEntreCaja' ]) }}
            <input type="hidden" value="{{ $corte->corte_id }}" id="corte_origen_id" name="corte_origen_id" />
            <div class="modal-body">

                <div class="form-row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('corte_destino_id', __('validation.attributes.corte_destino_id')) }}
                            {{ Form::select('corte_destino_id', $cortes, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('divisa_id', __('validation.attributes.divisa_id')) }}
                            {{ Form::select('divisa_id', $divisas, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe', __('validation.attributes.importe')) }}
                            {{ Form::text('importe', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>


{!! JsValidator::formRequest('App\Http\Requests\TransferenciaEntreCajasRequest', '#frmTransferenciaEntreCaja') !!}
<script>
    $(document).on('click', '.cancel-transferencia-entre-caja', function (event){
        event.preventDefault();
        var vId = $(this).data('id');

        Swal.fire({
            title: '¿Desea continuar?',
            text: 'La transferencia quedara cancelado.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#809fff',
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, continuar",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.value) {
                ShowLoading("Cancelando transferencia...");

                $.ajax({
                    url: "{{route('cortes.cancelar_transferencia_entre_caja')}}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        corte_id: "{{ $corte->corte_id }}",
                        transferencia_entre_caja_id: vId,
                    },
                    cache: false,
                    success: function (data) {

                        var vClass = data.Saved ? "success" : "error";

                        if(data.Saved)
                        {
                            $('#tab-traspasos-entre-cajas').html(data.Html);
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

    $(document).on('click', '.print-transferencia-retiro', function (event){
        event.preventDefault();
        var vId = $(this).data('id');
        MyToast('Notificacion', 'En construccio, id: ' + vId, 'info');
    })

    var options_transferencia = {
        beforeSubmit: function (arr, $form, options) {
            var vSubmit = $("#frmTransferenciaEntreCaja").valid();

            if (vSubmit) {
                ShowLoading('Actualizando información...');
                $('#frmTransferenciaEntreCaja button[type=submit], #frmTransferenciaEntreCaja input[type=submit]').prop('disabled', true);
            }

            return vSubmit;
        },
        success: function (data) {
            console.log(data);
            var vMessageClass = data.Saved ? 'success' : 'error';
            MyToast('Notificación', data.Message, vMessageClass);

            $('#frmTransferenciaEntreCaja button[type=submit], #frmTransferenciaEntreCaja input[type=submit]').prop('disabled', false);

            if(data.Saved)
            {
                $('#modalTransferenciaEntreCaja').modal('hide');
                setTimeout(function(){
                    $('#tab-traspasos-entre-cajas').html(data.Html);
                }, 1000)
            }
        }, error: function (data)
        {
            console.log(data);
            MyToast("Notificación", "Ocurrio un error", "error");
            $('#frmTransferenciaEntreCaja button[type=submit], #frmTransferenciaEntreCaja input[type=submit]').prop('disabled', false);
        }
    };

    $('#frmTransferenciaEntreCaja').ajaxForm(options_transferencia);
</script>

