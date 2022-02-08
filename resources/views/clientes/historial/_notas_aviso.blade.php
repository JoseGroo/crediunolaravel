<button id="agregarNuevaNotaAviso" class="btn-sm btn-success">Agregar nota</button>

@if($model && !$model->isEmpty())
    <div class="table-responsive m-t-lg">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Nota</th>
                <th>Creada por</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
                   @foreach ($model as $item)
                       <tr>
                           <td>{{ $item->nota }}</td>
                           <td>{{ $item->tbl_creado_por->nombre_completo }}</td>
                           <td>{{ \Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y h:i:s a') }}</td>
                       </tr>
                   @endforeach
            </tbody>
        </table>
    </div>
@endunless
