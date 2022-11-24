

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="ligas-avales-tab" data-toggle="tab" href="#ligas-avales" role="tab" aria-controls="home" aria-selected="true">Avales</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="ligas-referencias-tab" data-toggle="tab" href="#ligas-referencias" role="tab" aria-controls="profile" aria-selected="false">Referencias</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="ligas-avalados-tab" data-toggle="tab" href="#ligas-avalados" role="tab" aria-controls="contact" aria-selected="false">Avalados</a>
    </li>
</ul>
<div class="tab-content" id="ligas">
    <div class="tab-pane fade show active" id="ligas-avales" role="tabpanel" aria-labelledby="home-tab">
        @if($avales && !$avales->isEmpty())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Prestamo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($avales as $item)
                        @if($item->tbl_aval != null)
                            <tr>
                                <td>{{ $item->tbl_aval->full_name }}</td>
                                <td>{{ $item->folio }}</td>
                                <td><a target="_blank" href="{{ route('clientes.details', $item->tbl_aval->cliente_id) }}">Ver cliente</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay información para mostrar.</p>
        @endunless
    </div>
    <div class="tab-pane fade" id="ligas-referencias" role="tabpanel" aria-labelledby="profile-tab">
        @if($referencias && !$referencias->isEmpty())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($referencias as $item)
                        <tr>
                            <td>{{ $item->tbl_cliente->full_name }}</td>
                            <td><a target="_blank" href="{{ route('clientes.details', $item->cliente_id) }}">Ver cliente</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay información para mostrar.</p>
        @endunless
    </div>
    <div class="tab-pane fade" id="ligas-avalados" role="tabpanel" aria-labelledby="contact-tab">
        @if($avalados && !$avalados->isEmpty())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Prestamo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($avalados as $item)
                        @if($item->tbl_cliente != null)
                            <tr>
                                <td>{{ $item->tbl_cliente->full_name }}</td>
                                <td>{{ $item->folio }}</td>
                                <td><a target="_blank" href="{{ route('clientes.details', $item->cliente_id) }}">Ver cliente</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay información para mostrar.</p>
        @endunless
    </div>
</div>
