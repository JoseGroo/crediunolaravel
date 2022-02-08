<?php

namespace App\Http\Controllers;


use App\Enums\catalago_sistema;
use App\Enums\estatus_movimientos_corte;
use App\Enums\formas_pago;
use App\Enums\movimiento_bitacora;
use App\Enums\tipos_movimientos_corte;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\CobroOtrosConceptosRequest;
use App\tbl_ciudades;
use App\tbl_cobro_otros_conceptos;
use App\tbl_cortes;
use App\tbl_movimientos_corte;
use App\tbl_sucursales;
use Auth;
use Illuminate\Http\Request;

class CobroConceptosController extends Controller
{
    private $catalago_sistema = catalago_sistema::Sucursales;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cobro()
    {
        if(!auth()->user()->tiene_corte_abierto){
            \request()->session()->flash('error_message', 'No tiene corte abierto para realizar esta operación.');
            return redirect()->route('home');
        }

        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol, HelperCrediuno::$admin_rol, HelperCrediuno::$ventanilla]);

        $cobro_otro_concepto = session('cobro_otro_concepto');

        return view('cobro_otros_conceptos.cobro')
            ->with(compact('cobro_otro_concepto'));
    }

    public function cobro_post(CobroOtrosConceptosRequest $request)
    {
        if(!auth()->user()->tiene_corte_abierto){
            \request()->session()->flash('error_message', 'No tiene corte abierto para realizar esta operación.');
            return redirect()->route('home');
        }

        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol, HelperCrediuno::$admin_rol, HelperCrediuno::$ventanilla]);

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_cobro_otros_conceptos($data_model);

        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_cobro_otros_conceptos::create($model);

        $model->paga_con = $request->paga_con;
        $model->cambio = $model->paga_con - $model->importe;

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $operacion = new tbl_movimientos_corte();
        $operacion->cliente_id = 0;
        $operacion->tipo = tipos_movimientos_corte::CobroOtroConcepto;
        $operacion->importe = $model->importe;
        $operacion->corte_id = auth()->user()->corte_id;;
        $operacion->metodo_pago = formas_pago::Efectivo;
        $operacion->external_id = $model->cobro_otro_concepto_id;
        $operacion->estatus = estatus_movimientos_corte::Activo;
        $operacion->activo = true;
        $operacion->creado_por = auth()->user()->id;
        $operacion->fecha_creacion = $datetime_now;

        $response = tbl_movimientos_corte::create($operacion);

        HelperCrediuno::save_bitacora($model->cobro_otro_concepto_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);

        \request()->session()->flash('cobro_otro_concepto', $model);
        return redirect()->route('cobro_otros_conceptos.cobro');
    }

    public function download_pdf(Request $request){
        $model = tbl_cobro_otros_conceptos::get_by_id($request->cobro_otro_concepto_id);
        $model->paga_con = $request->paga_con;
        $model->cambio = $request->cambio;

        $data = [
            'model' => $model,
            'user' => Auth::user()
        ];

        return HelperCrediuno::generate_pdf($data, 'cobro_otros_conceptos.pdf_cobro', 'ticket-cobro-otro-concepto');
    }
}
