<div class="table-responsive mt-3">
    <table class="table table-bordered table-striped" id="tablePagos">
        <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th># Prestamo</th>
            <th class="text-center">
                <div style="line-height: 1.23;" class="checkbox checkbox-success">
                    <input type="checkbox" style="position: relative; margin-left: -10px;" name="select_all" id="select_all" class="check-box-value" placeholder="">
                    <label for="select_all"></label>
                </div>
            </th>
            <th># Recibo</th>
            <th>Día de pago</th>
            <th>Fecha de pago</th>
            <th>Capital</th>
            <th>Interes</th>
            <th>IVA</th>
            <th>Total</th>
            <th>Tipo</th>
            <th>Abono</th>
        </tr>
        </thead>
        <tbody>
        @php
            $renglon = 1;
            $bg_index = 0;
            $colors = Config::get('constants.colors');
        @endphp
        @foreach($prestamos as $prestamo)
            @php
                $bg_class = '';
                $text_class = '';
                if($prestamos->count() > 1)
                {
                    $bg_class = $colors[$bg_index];
                    $text_class = 'text-white';
                }
                $bg_index++;
            @endphp
            @foreach($prestamo->tbl_adeudos as $item)
                @php

                    $class_tipo_adeudo = $item->tipo == \App\Enums\tipo_adeudo::Recibo ? 'adeudo-recibo-empeno' : '';
                    $checked = $item->checked ? "checked" : "";
                    $disabled_pago = !$item->checked;
                    $importe_acreditar = $item->checked ? $item->acreditar : "";
                @endphp
                @if($item->tbl_cargo && $item->tbl_cargo->estatus == \App\Enums\estatus_adeudos::Vigente)
                    @php
                        $checked_cargo = $item->tbl_cargo->checked ? "checked" : "";
                        $disabled_pago_cargo = !$item->tbl_cargo->checked;
                        $importe_acreditar_cargo = $item->tbl_cargo->checked ? $item->tbl_cargo->acreditar : "";
                    @endphp
                    <tr class="{{ $bg_class }} text-danger tr-cargo" style="font-weight: bolder;">
                        <td>{{ $renglon }}</td>
                        <td>{{ $prestamo->folio }}</td>
                        <td class="text-center">
                            <div style="line-height: 1.23;" class="checkbox checkbox-success">
                                <input type="checkbox" style="position: relative; margin-left: -10px;" {{ $checked_cargo }} data-type="cargo" id="select_adeudo{{ $renglon }}" value="{{ $item->tbl_cargo->cargo_id }}" class="check-box-value adeudo-checkbox" placeholder="">
                                <label for="select_adeudo{{ $renglon }}"></label>
                            </div>
                        </td>
                        <td>{{ $item->numero_pago }} - {{ $item->tbl_cargo->cargo_id }}</td>
                        <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                        <td class="fecha-pago">{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                        <td>$0.00</td>
                        <td>@money_format($item->tbl_cargo->interes)</td>
                        <td>@money_format($item->tbl_cargo->iva)</td>
                        <td class="importe-total" data-importe-total="{{ $item->tbl_cargo->importe_total }}">@money_format($item->tbl_cargo->importe_total)</td>
                        <td>Cargo</td>
                        <td>{{ Form::text('abono', $importe_acreditar_cargo, [ 'class' => 'form-control just-decimal abono-recibo', 'style' => 'color: #000;', 'disabled' => $disabled_pago_cargo ]) }}</td>
                    </tr>
                    @php
                        $renglon++;
                    @endphp
                @endif
                @if($item->estatus == \App\Enums\estatus_adeudos::Vigente)
                    <tr class="{{ $class_tipo_adeudo }} {{ $bg_class }} {{ $text_class }}">
                        <td>{{ $renglon }}</td>
                        <td>{{ $prestamo->folio }}</td>
                        <td class="text-center">
                            <div style="line-height: 1.23;" class="checkbox checkbox-success">
                                <input type="checkbox" style="position: relative; margin-left: -10px;" {{ $checked }} data-type="recibo" id="select_adeudo{{ $renglon }}" value="{{ $item->adeudo_id }}" class="check-box-value adeudo-checkbox" placeholder="">
                                <label for="select_adeudo{{ $renglon }}"></label>
                            </div>
                        </td>
                        <td>{{ $item->numero_pago }}</td>
                        <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                        <td class="fecha-pago">{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                        <td>@money_format($item->capital)</td>
                        <td>@money_format($item->interes)</td>
                        <td>@money_format($item->IVA)</td>
                        <td class="importe-total" data-importe-total="{{ $item->importe_total }}">@money_format($item->importe_total)</td>
                        <td>Recibo</td>
                        <td>{{ Form::text('abono', $importe_acreditar, [ 'class' => 'form-control just-decimal abono-recibo', 'style' => 'color: #000;', 'disabled' => $disabled_pago ]) }}</td>
                    </tr>
                    @php
                        $renglon++;
                    @endphp
                @endif
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
