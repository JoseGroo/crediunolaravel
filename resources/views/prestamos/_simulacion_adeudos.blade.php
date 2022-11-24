

<div class="form-row">
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <h5>Datos del prestamo</h5>
    </div>
    <div class="col mt-2">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-center">Pagos</h5>
                <hr>
                <p class="card-text text-monospace">{{ $adeudos->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col mt-2">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-center">Total por pago</h5>
                <hr>
                <p class="card-text text-monospace">@money_format($adeudos->first()->importe_total)</p>
            </div>
        </div>
    </div>
    <div class="col mt-2">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-center">Total a pagar</h5>
                <hr>
                <p class="card-text text-monospace">@money_format($adeudos->sum('importe_total'))</p>
            </div>
        </div>
    </div>
    <div class="col mt-2">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-center">Interes por plazo</h5>
                <hr>
                <p class="card-text text-monospace">@money_format($adeudos->first()->interes)</p>
            </div>
        </div>
    </div>
    <div class="col mt-2">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-center">Interes total</h5>
                <hr>
                <p class="card-text text-monospace">@money_format($adeudos->sum('interes'))</p>
            </div>
        </div>
    </div>
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
                    @foreach($adeudos as $item)
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

<div class="form-row">
    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm btn-primary">Descargar tabla amortización</button>
        </div>
    </div>
</div>
