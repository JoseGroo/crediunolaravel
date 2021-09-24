@php
    $medio_publicitario_actuales = $model_actuales['medio_publicitario'] ?? '';


    $medio_publicitario_anteriores = $model_anteriores['medio_publicitario'] ?? '';


@endphp

<div class="form-row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    Datos actuales
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    @php
                        $background_color_cambios = $medio_publicitario_actuales != $medio_publicitario_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('medio_publicitario', 'Medio publicitario') }}
                            <div class="form-control-plaintext">{{ $medio_publicitario_actuales }}</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    Datos anteriores
                </h5>
            </div>
            <div class="card-body">

                <div class="form-row">

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('medio_publicitario', 'Medio publicitario') }}
                            <div class="form-control-plaintext">{{ $medio_publicitario_anteriores }}</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>