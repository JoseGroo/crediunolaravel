<div class="row mb-3">
    <div class="col-6">
        <button class="btn btn-info w-100" id="btnImprimirCorte" type="button"><i class="mdi mdi-printer"></i> Imprimir corte</button>
    </div>
    @if(!$model->cerrado)
        <div class="col-6">
            <button class="btn btn-danger w-100" id="btnCerrarCorte" type="button"><i class="mdi mdi-bank-remove"></i> Cerrar corte</button>
        </div>
    @endif
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Folio</h5>
                <hr>
                <p class="card-text text-monospace">{{ $model->corte_id }}</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Empleado</h5>
                <hr>
                <p class="card-text text-monospace">
                    {{ $model->tbl_usuario->nombre }} {{ $model->tbl_usuario->apellido_paterno }}
                </p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Fecha de inicio</h5>
                <hr>
                <p class="card-text text-monospace">{{ Carbon\Carbon::parse($model->fecha_creacion)->format('d/m/Y h:i:s a') }}</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Fecha de cierre</h5>
                <hr>
                @if($model->fecha_cierre)
                    <p class="card-text text-monospace">{{ Carbon\Carbon::parse($model->fecha_cierre)->format('d/m/Y h:i:s a') }}</p>
                @else
                    <p class="card-text text-monospace">Sin fecha de cierre</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Estatus</h5>
                <hr>
                <p class="card-text text-monospace">{{ $model->cerrado ? 'Cerrado' : 'Activo' }}</p>
            </div>
        </div>
    </div>
</div>
