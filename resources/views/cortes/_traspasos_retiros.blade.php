
@if(!$corte->cerrado)
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" data-toggle="modal" data-target="#modalTransferencia" class="btn btn-sm btn-info">Hacer transferencia/rertiro</button>
        </div>
    </div>
@endif

<div class="table-responsive m-t-lg table-fix-head">

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Fondo</th>
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
                    <td>{{ \App\Enums\tipo_transferencia_fondo::getDescription($item->tipo) }}</td>
                    <td>
                        {{ $item->tbl_fondo->fondo }}
                    </td>
                    <td>
                        @money_format($item->importe)
                    </td>
                    <td>
                        {{ $item->tbl_divisa->divisa }}
                    </td>
                    <td>
                        @if($item->estatus == \App\Enums\estatus_movimientos_corte::Activo)
                            <a href="#" class="btn btn-sm btn-info print-transferencia-retiro" data-id="{{ $item->transferencia_fondo_id }}"><i class="mdi mdi-printer"></i> Imprirmir</a>
                        @endif
                    </td>
                    @if(!$corte->cerrado)
                        <td>
                            @if($item->estatus == \App\Enums\estatus_movimientos_corte::Activo)
                                <a href="#" class="btn btn-sm btn-danger cancel-transferencia-retiro" data-id="{{ $item->transferencia_fondo_id }}"><i class="mdi mdi-cancel"></i> Cancelar</a>
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

<div class="modal inmodal" id="modalTransferencia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInUp">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Transferencia/Retiro</h4>
            </div>
            {{ Form::open([ 'route' => ['cortes.hacer_transferencia_post' ], 'method' => 'POST', 'id' => 'frmTransferencia' ]) }}
            <input type="hidden" value="{{ $corte->corte_id }}" id="corte_id" name="corte_id" />
            <div class="modal-body">

                <div class="form-row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('tipo', __('validation.attributes.tipo')) }}
                            {{ Form::select('tipo', $tipos_transferencias, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('fondo_id', __('validation.attributes.fondo')) }}
                            {{ Form::select('fondo_id', $fondos, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
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


{!! JsValidator::formRequest('App\Http\Requests\TransferenciaRetiroRequest', '#frmTransferencia') !!}
<script>
    $(document).on('click', '.cancel-transferencia-retiro', function (event){
        event.preventDefault();
        var vId = $(this).data('id');

        Swal.fire({
            title: '¿Desea continuar?',
            text: 'La transferencia/retiro quedara cancelado.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#809fff',
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, continuar",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.value) {
                ShowLoading("Cancelando transferencia/retiro...");

                $.ajax({
                    url: "{{route('cortes.cancelar_transferencia_fondo')}}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        corte_id: "{{ $corte->corte_id }}",
                        transferencia_fondo_id: vId,
                    },
                    cache: false,
                    success: function (data) {

                        var vClass = data.Saved ? "success" : "error";

                        if(data.Saved)
                        {
                            $('#tab-traspasos-retiros').html(data.Html);
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
            var vSubmit = $("#frmTransferencia").valid();

            if (vSubmit) {
                ShowLoading('Actualizando información...');
                $('#frmTransferencia button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', true);
            }

            return vSubmit;
        },
        success: function (data) {
            console.log(data);
            var vMessageClass = data.Saved ? 'success' : 'error';
            MyToast('Notificación', data.Message, vMessageClass);

            $('#frmTransferencia button[type=submit], #frmTransferencia input[type=submit]').prop('disabled', false);

            if(data.Saved)
            {
                $('#modalTransferencia').modal('hide');
                setTimeout(function(){
                    $('#tab-traspasos-retiros').html(data.Html);
                }, 1000)
            }
        }, error: function (data)
        {
            console.log(data);
            MyToast("Notificación", "Ocurrio un error", "error");
            $('#frmTransferencia button[type=submit], #frmTransferencia input[type=submit]').prop('disabled', false);
        }
    };

    $('#frmTransferencia').ajaxForm(options_transferencia);
</script>

