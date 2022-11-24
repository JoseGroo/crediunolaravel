@if($model && !$model->isEmpty())
    <h5>Prestamos disponibles para reestructura</h5>
    <div class="table-responsive m-t-lg">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Cliente</th>
                <th>Folio</th>
                <th>Recibos</th>
                <th>Cargos</th>
                <th>Total deuda</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
                   @foreach ($model as $item)
                       @php
                           $total_adeudo = $item->total_cargos + $item->total_recibos;
                       @endphp
                       <tr>
                           <td>{{ $item->tbl_cliente->full_name }}</td>
                           <td>{{ $item->folio }}</td>
                           <td>{{ $item->recibos_pendientes }}</td>
                           <td>{{ $item->numero_cargos }}</td>
                           <td>@money_format($total_adeudo)</td>
                           <td>
                               @if($item->numero_cargos <= 0)
                                    <a href="{{ route('prestamos.reestructurar', $item->prestamo_id) }}">Reestructurar</a>
                               @endif
                           </td>
                       </tr>
                   @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $('.fancybox').fancybox();
    </script>
@else
    <p>No se encontraron prestamos para reestructurar, Puede generar un prestamo <a href="{{ route('prestamos.generar', $cliente_id) }}">haciendo click aqui.</a></p>
@endunless
