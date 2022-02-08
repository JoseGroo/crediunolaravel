
@if($model && !$model->isEmpty())
    <div class="table-responsive m-t-lg">
        <table class="table table-hover">
            <thead>
            <tr>
                <th># Prestamo</th>
                <th>Tipo</th>
                <th>Periodo</th>
                <th>Duración</th>
                <th>Capital solicitado</th>
                <th>Fecha</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                   @foreach ($model as $item)
                       <tr>
                           <td>{{ $item->folio }}</td>
                           <td>{{ $item->tbl_interes->nombre }}</td>
                           <td>{{ \App\Enums\periodos_prestamos::getDescription($item->periodo) }}</td>
                           <td>{{ $item->duracion_text }}</td>
                           <td>@money_format($item->capital)</td>
                           <td>{{ \Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y h:i:s a') }}</td>
                           <td>
                               <a href="{{ route('clientes.historial.estado_prestamo', $item->prestamo_id) }}" class="btn btn-sm btn-white">Detalles</a>
                           </td>
                       </tr>
                   @endforeach
            </tbody>
        </table>
    </div>
@endunless
