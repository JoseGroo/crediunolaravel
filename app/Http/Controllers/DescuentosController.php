<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\estatus_descuentos;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\DescuentosRequest;
use App\Http\Requests\DiasFestivosRequest;
use App\tbl_descuentos;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DescuentosController extends Controller
{
    private $catalago_sistema = catalago_sistema::Descuentos;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $estatus = '';

        if($request->ajax())
        {
            $estatus = request('estatus');
        }else{
            $estatus = estatus_descuentos::Vigente;
        }

        $fecha_inicio = request('fecha_inicio');
        $fecha_fin = request('fecha_fin');


        $perPage = request('iPerPage') ?? 10;

        $model = tbl_descuentos::get_pagination($fecha_inicio, $fecha_fin, $estatus, $perPage);
        if($request->ajax()){
            return view('descuentos._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $estatus_descuentos = estatus_descuentos::toSelectArray();

        return view('descuentos.index')
            ->with(compact("model"))
            ->with(compact("estatus_descuentos"))
            ->with(compact("estatus"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        return view('descuentos.create');
    }

    public function create_post(DescuentosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_descuentos::check_if_exists($request->cliente_id, 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', 'El cliente seleccionado ya cuenta con un descuento vigente.');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = $request->except(['_token', '_method']);

        $model = new tbl_descuentos($data_model);
        $model->fecha_vigencia = Carbon::createFromFormat('d/m/Y', $model->fecha_vigencia);


        if($model->fecha_vigencia->format('d/m/Y') < $datetime_now->format('d/m/Y'))
        {
            return redirect()->back()->withInput(request()->all())
                ->with('error', 'La fecha de vigencia no puede ser antes de hoy.');
        }

        $model->estatus = estatus_descuentos::Vigente;
        $model->importe_acreditado = 0;
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_descuentos::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->descuento_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('descuentos.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_descuentos::get_by_id($id);

        if(!$model)
            abort(404);

        $model->fecha_vigencia = Carbon::parse($model->fecha_vigencia)->format('d-m-Y');
        return view('descuentos.details')
            ->with(compact('model'));
    }

    #region Ajax
    public function cancel(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){

            $model = tbl_descuentos::get_by_id($request->descuento_id);
            $model->estatus = estatus_descuentos::Cancelado;

            $response = tbl_descuentos::edit($model);

            if($response['saved'] == false)
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->descuento_id, movimiento_bitacora::CanceloDescuento, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved' => true,
                'Message'   => 'Se elimino correctamente la información.'
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => 'Ocurrio un error al intentar guardar la información.'
        ));
    }

    #endregion
}
