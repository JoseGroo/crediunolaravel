<div class="row m-t-md">

    <div class="col-md-4 col-sm-4 col-4">
        <div id="pagedList" class="pagedList" data-otf-target="#IndexList">
            {{ $model->links() }}
        </div>
    </div>

    <div class="col-md-4 col-sm-4 col-4">
        <div class="form-row no-gutters justify-content-center">
            <div class="form-group col-4 text-right">
                <span class="font-weight-bold mt-1 d-block">Mostrar </span>
            </div>
            <div class="form-group col-3">

                {{ Form::select('iPerPage', array(10 => 10, 25 => 25, 50 => 50, 100 => 100, 1000 => 1000), $perPage, ['class' => 'form-control form-control-sm filtrar']) }}
            </div>
            <div class="form-group col-4">
                <span class="font-weight-bold mt-1 d-block"> reigstros </span>
            </div>
        </div>

    </div>

    <div class="col-md-4 col-sm-4 col-12 text-right">
        <span class="mt-2 d-block">{{ $model->lastItem() > 0 ? $model->firstItem() : 0 }} - {{ $model->lastItem() ?? 0 }} de {{ $model->total() }} registros</span>
    </div>
</div>
