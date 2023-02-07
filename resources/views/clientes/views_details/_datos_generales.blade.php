@php
    $foto_perfil =  Storage::exists($model->foto) ? $model->foto : "public/user_profile/default.png";
@endphp
<div class="row">
    <div class="col-md-3">
        <div class="ibox-content no-padding text-center">
            <img alt="image" style="width: 163px; height: 163px;" class="img-fluid img-circle" src="{{ Storage::url($foto_perfil) }}">

            <h5 class="text-center">
                <strong>#{{ $model->cliente_id }} - {{ $model->nombre }} {{ $model->apellido_paterno }} {{ $model->apellido_materno }}</strong>
                <span id="spanEstatus">({{ \App\Enums\estatus_cliente::getDescription($model->estatus) }})</span>
            </h5>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-4">
                @if(Route::currentRouteName() == 'clientes.details')
                <p id="pLimiteCredito" style="cursor: pointer; display: table;">
                    <i class="mdi mdi-cash-usd" title="Limite de crédito"></i>
                    $<span id="spanLimiteCredito">{{ number_format($model->limite_credito, 2) }}</span>
                    <i class="mdi mdi-pencil" style="font-size: 12px; display: table-cell; vertical-align: middle;"></i>
                </p>
                @else
                    <p style="display: table;">
                        <i class="mdi mdi-cash-usd" title="Limite de crédito"></i>
                        $<span id="spanLimiteCredito">{{ number_format($model->limite_credito, 2) }}</span>
                    </p>
                @endif
            </div>

            <div class="col-md-4">
                <h5 class="text-muted">
                    <i class="mdi mdi-office-building" title="Sucursal"></i>
                    {{ $model->sucursal->sucursal }}
                </h5>
            </div>

            <div class="col-md-4">
                <p>
                    <strong>Medio publicitario: </strong>
                    {{ $model->medio_publicitario->medio_publicitario }}
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <strong>Grupo: </strong>
                    {{ $model->grupo->grupo ?? 'Sin grupo asignado' }}
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <i class="mdi mdi-cake" title="Fecha de nacimiento"></i>
                    {{ $model->fecha_nacimiento }} ({{ $model->edad }} Años)
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <i class="mdi mdi-ring" title="Estado civil"></i>
                    {{ \App\Enums\estado_civil::getDescription($model->estado_civil) }}
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <i class="mdi mdi-briefcase" title="Ocupación"></i>
                    {{ $model->ocupacion }}
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <i class="mdi mdi-gender-male-female" title="Sexo"></i>
                    {{ \App\Enums\sexo::getDescription($model->sexo) }}
                </p>
            </div>

            <div class="col-md-12">
                <p>
                    <i class="mdi mdi-map-marker" title="Dirección"></i>
                    {{ $model->pais }} {{ $model->estado->estado ?? '' }}, {{ $model->localidad }}, {{ $model->colonia }}, {{ $model->calle }} <strong>Num int:</strong> {{ $model->numero_interior }}
                    <strong>Num ext:</strong> {{ $model->numero_exterior }}, <strong>Entre calles:</strong> {{ $model->entre_calles }}, <strong>Señas particulares:</strong> {{ $model->senas_particulares }}
                    <strong>CP:</strong> {{ $model->codigo_postal }}
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <strong>Tiempo de residencia: </strong>
                    {{ $model->tiempo_residencia }} {{ $model->tiempo_residencia == null ? '' : \App\Enums\unidad_tiempo::getDescription($model->unidad_tiempo_residencia) }}
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <strong>Vivienda: </strong>
                    {{ $model->vivienda }}
                </p>
            </div>

            <div class="col-md-4">
                <p>
                    <strong>Renta: </strong>
                    ${{ number_format($model->renta ?? 0, 2) }}
                </p>
            </div>


        </div>
    </div>
</div>
