<div class="form-row">
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <h5>Información del prestamo</h5>
    </div>
</div>

<div class="form-row">
    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <label>@lang('validation.attributes.folio')</label>
            <div class="form-control-plaintext">{{ $model->folio }}</div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <label>@lang('validation.attributes.capital')</label>
            <div class="form-control-plaintext">@money_format($model->capital)</div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <label>@lang('validation.attributes.periodo')</label>
            <div class="form-control-plaintext">{{ \App\Enums\periodos_prestamos::getDescription($model->periodo) }}</div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <label>@lang('validation.attributes.plazo')</label>
            <div class="form-control-plaintext">{{ $model->plazo }}</div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <label>@lang('validation.attributes.tipo')</label>
            <div class="form-control-plaintext">{{ $model->tbl_interes->nombre }}</div>
        </div>
    </div>
</div>

@if($model->tbl_garantia)
    <div class="form-row">
        <div class="col-md-12"><hr></div>
        <div class="col-md-12 col-sm-12 col-12">
           <h5>@lang('validation.attributes.datos_garantia')</h5>
        </div>

        <div class="col-md-4 col-sm-6 col-12">
            <div class="form-group">
                <label>@lang('validation.attributes.tipo_garantia')</label>
                <div class="form-control-plaintext">{{ \App\Enums\tipos_garantia::getDescription($model->tbl_garantia->tipo) }}</div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-12">
            <div class="form-group">
                <label>@lang('validation.attributes.descripcion')</label>
                <div class="form-control-plaintext">{{ $model->tbl_garantia->descripcion }}</div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-12">
            <div class="form-group">
                <label>@lang('validation.attributes.valor')</label>
                <div class="form-control-plaintext">${{ number_format($model->tbl_garantia->valor, 2) }}</div>
            </div>
        </div>
    </div>
@endif

@if($model->tbl_aval)
    <div class="form-row">
        <div class="col-md-12"><hr></div>
        <div class="col-md-12 col-sm-12 col-12">
            <h5>@lang('validation.attributes.aval')</h5>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>@lang('validation.attributes.nombre')</label>
                <div class="form-control-plaintext">{{ $model->tbl_aval->full_name }}</div>
            </div>
        </div>
    </div>
@endif

<div class="form-row">
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <h5>Tabla de amortización</h5>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Recibo</th>
                        <th>Día</th>
                        <th>Fecha</th>
                        <th>Pago total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($model->tbl_adeudos as $item)
                        @php
                            $class_tipo_adeudo = $item->tipo == \App\Enums\tipo_adeudo::Recibo ? 'adeudo-recibo-empeno' : '';
                        @endphp
                        <tr class="{{ $class_tipo_adeudo }}">
                            <td>{{ $item->numero_pago }}</td>
                            <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                            <td>@money_format($item->importe_total)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<input type="hidden" id="hFolio" value="{{ $model->folio }}">
<input type="hidden" id="prestamo_id" name="prestamo_id" value="{{ $model->prestamo_id }}">

<input type="hidden" id="hCapital" value="@money_format($model->capital)">