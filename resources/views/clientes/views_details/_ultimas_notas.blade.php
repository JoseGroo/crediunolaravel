@if($notas && !$notas->isEmpty())
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Nota</th>
            <th>Usuario</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
               @foreach ($notas as $item)
                   @php
                       $fecha = Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y H:m:s');
                   @endphp
                   <tr>
                       <td>{{ $item->nota }}</td>
                       <td>{{ $item->usuario_creo->usuario }}</td>
                       <td>{{ $fecha }}</td>
                   </tr>
               @endforeach
        </tbody>
    </table>
</div>
@else
    <p>No tiene notas para mostrar.</p>
@endunless
