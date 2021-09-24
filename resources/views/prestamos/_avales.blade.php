<div class="table-responsive">
    <table class="table table-bordered table-striped" id="tAvales">
        <thead class="thead-dark">
        <tr>
            <th>Nombre</th>
            <th>Colonia</th>
            <th>Seleccionar</th>
        </tr>
        </thead>
        <tbody>
        @foreach($model as $item)
            @php
                $text_select = $aval_id == $item->aval_id ? 'Seleccionado' : 'Seleccionar';
                $button_class = $aval_id == $item->aval_id ? 'btn-success' : 'btn-primary';
            @endphp
            <tr>
                <td>{{ $item->full_name }}</td>
                <td>{{ $item->colonia }}</td>
                <td>
                    <button type="button" class="btn btn-sm {{ $button_class }} seleccionar-aval" data-id="{{ $item->aval_id }}" data-nombre="{{ $item->full_name }}">{{ $text_select }}</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>