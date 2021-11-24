
@php
    $foto_perfil =  Storage::exists($item->foto) ? $item->foto : "public/user_profile/default.png";
@endphp

<div class="form-row">
    <div class="col-12">
        <img src="{{ Storage::url($foto_perfil) }}" class="rounded float-left" style="height: 40px;">
        <span class="p-0 m-0">#{{ $item->cliente_id }} - {{ $item->full_name }}</span>
        <br>
        <p class="p-0 m-0"><i class="mdi mdi-office-building" title="Sucursal"></i>{{ $item->sucursal->sucursal }}</p>
    </div>
</div>


<!--

<div class="form-row d-table">
    <div class="col-12 d-table-row">
        <img src="{{ Storage::url($foto_perfil) }}" class="rounded float-left d-table-cell align-middle" style="height: 40px;">
        <span class="d-table-cell align-middle">#{{ $item->cliente_id }} - {{ $item->full_name }}</span>
        <span class="d-table-cell align-middle">#{{ $item->sucursal->sucursal }}</span>
    </div>
</div>-->
