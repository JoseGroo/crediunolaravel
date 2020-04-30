@php
    $grupo_actuales = isset($model_actuales['grupo']) ? $model_actuales['grupo'] : '';
    
    $grupo_anteriores = isset($model_anteriores['grupo']) ? $model_anteriores['grupo'] : '';
    
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
                        $background_color_cambios = $grupo_actuales != $grupo_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('grupo', 'Grupo') }}
                            <div class="form-control-plaintext">{{ $grupo_actuales }}</div>
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
                            {{ Form::label('grupo', 'Grupo') }}
                            <div class="form-control-plaintext">{{ $grupo_anteriores }}</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>