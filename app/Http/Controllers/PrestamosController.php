<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\dias_semana;
use App\Enums\estatus_adeudos;
use App\Enums\estatus_movimientos_corte;
use App\Enums\estatus_prestamo;
use App\Enums\formas_pago;
use App\Enums\movimiento_bitacora;
use App\Enums\periodos_prestamos;
use App\Enums\tipo_adeudo;
use App\Enums\tipos_garantia;
use App\Enums\tipos_movimientos_corte;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\PrestamoRequest;
use App\tbl_adeudos;
use App\tbl_avales;
use App\tbl_clientes;
use App\tbl_dias_festivos;
use App\tbl_garantias;
use App\tbl_intereses;
use App\tbl_movimientos_corte;
use App\tbl_prestamos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PrestamosController extends Controller
{
    private $catalago_sistema = catalago_sistema::Clientes;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generar($cliente_id)
    {
        $intereses = tbl_intereses::get_list()->pluck('nombre', 'interes_id');
        $garantias = tbl_garantias::get_list_by_cliente_id($cliente_id);
        $cliente = tbl_clientes::get_by_id($cliente_id);
        $dias_semana = dias_semana::toSelectArray();
        $periodos = periodos_prestamos::toSelectArray();
        $tipos_garantia = tipos_garantia::toSelectArray();


        return view('prestamos.generar')
            ->with(compact('intereses'))
            ->with(compact('dias_semana'))
            ->with(compact('cliente'))
            ->with(compact('tipos_garantia'))
            ->with(compact('garantias'))
            ->with(compact('periodos'));
    }

    public function generar_post(PrestamoRequest $request)
    {

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_prestamos($data_model);
        $interes = tbl_intereses::get_by_id($request->interes_id);


        $garantia_id = $request->garantia_existente == 1 ? $request->garantia_id : 0;
        if($request->garantia_existente != 1 && !empty($request->tipo) && !empty($request->descripcion) && !empty($request->valor))
        {
            $nueva_garantia = new tbl_garantias();
            $nueva_garantia->tipo = $request->tipo;
            $nueva_garantia->descripcion = $request->descripcion;
            $nueva_garantia->valor = $request->valor;
            $nueva_garantia->cliente_id = $request->cliente_id;
            $nueva_garantia->activo = true;
            $nueva_garantia->creado_por = auth()->user()->id;
            $nueva_garantia->fecha_creacion = $datetime_now;
            $response = tbl_garantias::create($nueva_garantia);

            if(!$response['saved'])
            {
                return redirect()->back()->withInput()
                    ->with('error',$response['error']);
            }
            $garantia_id = $nueva_garantia->garantia_id;
        }

        $model->taza_iva = $request->aplico_taza_preferencial == 1 ? $request->taza_iva : $interes->interes_mensual;
        $model->aplico_taza_preferencial = $request->aplico_taza_preferencial == 1 ? true : false;
        $model->garantia_id = $garantia_id;
        $importe_manejo_cuenta = ($model->capital * $interes->manejo_cuenta)/100;
        $importe_manejo_cuenta += ($importe_manejo_cuenta * $interes->iva_otros_conceptos)/100;
        $importe_comision_apertura = ($model->capital * $interes->comision_apertura)/100;
        $importe_comision_apertura +=($importe_comision_apertura * $interes->iva_otros_conceptos)/100;
        $importe_gastos_papeleria = ($model->capital * $interes->gastos_papeleria)/100;
        $importe_gastos_papeleria +=($importe_gastos_papeleria*$interes->iva_otros_conceptos)/100;
        $importe_gastos_cobranza = ($model->capital * $interes->gastos_cobranza)/100;
        $importe_gastos_cobranza += ($importe_gastos_cobranza*$interes->iva_otros_conceptos)/100;
        $importe_seguro_desempleo = ($model->capital * $interes->seguro_desempleo)/100;
        $importe_seguro_desempleo += ($importe_seguro_desempleo * $interes->iva_otros_conceptos)/100;
        //Capital concepto es por la que se sacan los pagos
        $model->capital_concepto = $model->capital + $importe_manejo_cuenta + $importe_comision_apertura + $importe_gastos_papeleria + $importe_gastos_cobranza + $importe_seguro_desempleo;
        $model->manejo_cuenta = $importe_manejo_cuenta;
        $model->comision_apertura = $importe_comision_apertura;
        $model->gastos_papeleria = $importe_gastos_papeleria;
        $model->gastos_cobranza = $importe_gastos_cobranza;
        $model->seguro_desempleo = $importe_seguro_desempleo;
        $model->cobrar_dias_festivos = $request->cobrar_dias_festivos == "1" ? true :false;
        $model->estatus = estatus_prestamo::Generado;
        $model->dia_pago_manual = $model->dia_pago_manual ? Carbon::createFromFormat('d/m/Y', $model->dia_pago_manual) : null;
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;
        $model->plazo = $model->interes_id == 2 ? 1 : $model->plazo;

        $response = tbl_prestamos::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        switch ($model->periodo){
            case periodos_prestamos::Diario:
                $cantidad_pagos = $model->plazo;
                $sumar_dias_primer_pago = 1;
                $sumar_dias_fecha_limite = 1;
                break;
            case periodos_prestamos::Semanal;
                $cantidad_pagos = $model->plazo * 4;
                $sumar_dias_primer_pago = 4;
                $sumar_dias_fecha_limite = 7;
                break;
            case periodos_prestamos::Quincenal:
                $cantidad_pagos = $model->plazo * 2;
                $sumar_dias_primer_pago = 7;
                $sumar_dias_fecha_limite = 0;
                break;
            case periodos_prestamos::Mensual:
                $cantidad_pagos = $model->plazo;
                $sumar_dias_primer_pago = 10;
                $sumar_dias_fecha_limite = 0;
                break;
        }

        $importe_interes = ($model->taza_iva * $model->capital_concepto)/100;

        $importe_iva = ($importe_interes * $interes->iva)/100;
        $adeudo_iva = $model->plazo * $importe_iva;
        $adeudo_interes = $model->plazo * $importe_interes;

        $adeudo_capital = $model->capital_concepto / $cantidad_pagos;

        $fecha_limite_pago = $model->dia_pago_manual == null ? HelperCrediuno::get_datetime() : Carbon::parse($model->dia_pago_manual);
        $dias_festivos = tbl_dias_festivos::get_list_by_date($fecha_limite_pago);
        $importe_total_por_pago = 0;
        for ($i = 0; $i < $cantidad_pagos; $i++)
        {
            if($i == 0 && $model->dia_pago_manual == null)
                $fecha_limite_pago->addDays($sumar_dias_primer_pago);

            if($model->periodo == periodos_prestamos::Quincenal && $i == 0 && $model->dia_pago_manual == null)
                $fecha_limite_pago = HelperCrediuno::obtener_fecha_quincenal($fecha_limite_pago);

            $response_dias_festivos = HelperCrediuno::checar_dia_festivo_y_descanso($dias_festivos, $model->dia_descanso, $fecha_limite_pago, $model->cobrar_dias_festivos);
            $fecha_limite_pago = $response_dias_festivos['nueva_fecha'];

            $numero_pago = $i + 1 . '/' . $cantidad_pagos;
            $adeudo = new tbl_adeudos();
            $adeudo->capital = $model->interes_id == 2 ? 0 : $adeudo_capital;
            $adeudo->interes = $adeudo_interes/$cantidad_pagos;
            $adeudo->iva = $adeudo_iva/$cantidad_pagos;
            $adeudo->importe_total = ($model->interes_id == 2 ? 0 : $adeudo->capital) + $adeudo->interes + $adeudo->iva;
            $importe_total_por_pago = $adeudo->importe_total;
            $adeudo->fecha_limite_pago = $fecha_limite_pago->format('Y-m-d');
            $adeudo->prestamo_id = $model->prestamo_id;
            $adeudo->numero_pago = $numero_pago;
            $adeudo->estatus = estatus_adeudos::Vigente;
            $adeudo->tipo = $model->interes_id == 2 ? tipo_adeudo::Recibo : null;
            $adeudo->activo = true;
            $adeudo->creado_por = auth()->user()->id;
            $adeudo->fecha_creacion = $datetime_now;
            $response = tbl_adeudos::create($adeudo);

            if($model->interes_id == 2){

                $adeudo = new tbl_adeudos();
                $adeudo->capital = $adeudo_capital;
                $adeudo->interes = 0;
                $adeudo->iva = 0;
                $adeudo->importe_total = $adeudo->capital;
                $adeudo->fecha_limite_pago = $fecha_limite_pago->format('Y-m-d');
                $adeudo->prestamo_id = $model->prestamo_id;
                $adeudo->numero_pago = $numero_pago;
                $adeudo->estatus = estatus_adeudos::Vigente;
                $adeudo->tipo = tipo_adeudo::Capital;
                $adeudo->activo = true;
                $adeudo->creado_por = auth()->user()->id;
                $adeudo->fecha_creacion = $datetime_now;
                $response = tbl_adeudos::create($adeudo);
            }

            $fecha_limite_pago = $fecha_limite_pago->addDays($sumar_dias_fecha_limite);

            if($model->periodo == periodos_prestamos::Semanal)
            {
                $fecha_limite_pago = $fecha_limite_pago->addDays(-$response_dias_festivos['dias_recorridos']);
            }else if($model->periodo == periodos_prestamos::Quincenal)
            {
                $fecha_limite_pago = HelperCrediuno::obtener_fecha_quincenal($fecha_limite_pago);
            }else if($model->periodo == periodos_prestamos::Mensual)
            {
                $fecha_limite_pago = $fecha_limite_pago->addDays(-$response_dias_festivos['dias_recorridos']);
                $fecha_limite_pago = $fecha_limite_pago->addMonths(1);
            }
            $nombre_dia = HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($adeudo->fecha_limite_pago)->format('l')];
            //print('Pago ' . $numero_pago . ' - $'  . $adeudo->importe_total . ' Limite de pago: '. 'Dia: '.$nombre_dia. ' - ' . $adeudo->fecha_limite_pago . '<br>');

        }
        //dd($cantidad_pagos);

        if($model->interes_id != 2)
        {
            $model->importe_por_pago = $importe_total_por_pago;
            $model->total_pagos = $cantidad_pagos;
            tbl_prestamos::edit($model);
        }

        \request()->session()->flash('message_prestamo', 'Se genero correctamente el prestamo con folio: ' . $model->folio);

        HelperCrediuno::save_bitacora($model->prestamo_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('clientes.details', $model->cliente_id);
    }

    public function entregar($cliente_id)
    {
        if(!auth()->user()->tiene_corte_abierto) {
            \request()->session()->flash('error_message', 'Abra un corte para realizar esa acción.');
            return redirect()->route('clientes.details', $cliente_id);
        }

        $cliente = tbl_clientes::get_by_id($cliente_id);
        $prestamos = tbl_prestamos::get_list_sin_entregar_by_cliente_id($cliente_id);


        return view('prestamos.entregar')
            ->with(compact('prestamos'))
            ->with(compact('cliente'));
    }

    public function entregar_post(Request $request)
    {
        $datetime_now = HelperCrediuno::get_datetime();
        $prestamo = tbl_prestamos::get_by_id($request->prestamo_id);

        if($prestamo->estatus != estatus_prestamo::Generado)
        {
            return redirect()->back()->withInput()
                ->with('error', 'El prestamo que esta intentando entregar/cancelar, no esta disponible.');
        }

        $prestamo->estatus = $request->action == 1 ? estatus_prestamo::Vigente : estatus_prestamo::Cancelado;
        $prestamo->usuario_entrego_id = auth()->user()->id;
        $response = tbl_prestamos::edit($prestamo);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }
        $movimiento_bitacora = movimiento_bitacora::CanceloPrestamo;
        if($prestamo->estatus == estatus_prestamo::Vigente)
        {

            $movimiento_bitacora = movimiento_bitacora::EntregoPrestamo;

            $movimiento_corte = new tbl_movimientos_corte();
            $movimiento_corte->cliente_id = $prestamo->cliente_id;
            $movimiento_corte->importe = $prestamo->capital;
            $movimiento_corte->corte_id = auth()->user()->tbl_corte->corte_id;
            $movimiento_corte->tipo = tipos_movimientos_corte::EntregaPrestamo;
            $movimiento_corte->metodo_pago = formas_pago::Efectivo;
            $movimiento_corte->external_id = $prestamo->prestamo_id;
            $movimiento_corte->estatus = estatus_movimientos_corte::Activo;
            $movimiento_corte->activo = true;
            $movimiento_corte->creado_por = auth()->user()->id;
            $movimiento_corte->fecha_creacion = $datetime_now;

            $response = tbl_movimientos_corte::create($movimiento_corte);
        }
        if($prestamo->estatus == estatus_prestamo::Vigente)
        {
            \request()->session()->flash('capital_entregada', $prestamo->capital);
        }else{
            \request()->session()->flash('success_message', 'Se cancelo correctamente el prestamo con folio: ' . $prestamo->folio);
        }


        HelperCrediuno::save_bitacora($prestamo->prestamo_id, $movimiento_bitacora, $this->catalago_sistema, null, null);
        return redirect()->route('prestamos.entregar', $prestamo->cliente_id);
    }

    public function get_prestamo_by_id(Request $request)
    {
        $model = tbl_prestamos::get_by_id($request->prestamo_id);

        return view('prestamos._prestamo_info')
            ->with(compact('model'));
    }

    public function get_list_avales_by_name(Request $request)
    {
        $model = tbl_avales::get_list_by_name($request->nombre);
        $aval_id = $request->aval_id;
        return view('prestamos._avales')
            ->with(compact('aval_id'))
            ->with(compact('model'));
    }

    public function get_pagos_by_prestamo_id(Request $request)
    {
        $prestamo = tbl_prestamos::get_by_id($request->id);
        $prestamos = collect(new tbl_prestamos);
        if($request->id == "")
        {
            $prestamos = tbl_prestamos::get_list_vigentes_by_cliente_id($request->cliente_id);
        }else{
            $prestamos->add($prestamo);
        }

        $html_pagos = view('prestamos._pagos')
            ->with(compact('prestamos'))
            ->render();

        $html_detalles_prestamo = view('prestamos._detalles_prestamo')
            ->with(compact('prestamos'))
            ->render();

        return \Response::json(array(
            'HtmlPagos'             => $html_pagos,
            'HtmlDetallesPrestamo'  => $html_detalles_prestamo
        ));
    }

    public function get_pagos_acreditar_cantidad(Request $request)
    {
        $prestamo = tbl_prestamos::get_by_id($request->id);
        $prestamos = collect(new tbl_prestamos);
        if($request->id == "")
        {
            $prestamos = tbl_prestamos::get_list_vigentes_by_cliente_id($request->cliente_id);
        }else{
            $prestamos->add($prestamo);
        }

        $today = Carbon::createFromFormat('Y-m-d', HelperCrediuno::get_datetime()->format('Y-m-d'));
        $importe_acreditar = $request->importe_acreditar;

        #region Se acreditan adeudos a la fecha de hoy
        if($importe_acreditar > 0)
        {
            foreach ($prestamos as $item)
            {
                foreach ($item->tbl_adeudos as $adeudo) {

                    if($adeudo->fecha_limite_pago <= $today && $importe_acreditar > 0)
                    {
                        if($importe_acreditar >= $adeudo->importe_total)
                        {
                            $adeudo->acreditar = number_format($adeudo->importe_total, 2);
                            $adeudo->checked = true;
                            $importe_acreditar -= $adeudo->importe_total;
                        }
                        else if($importe_acreditar > 0)
                        {
                            $adeudo->acreditar = number_format($importe_acreditar, 2);
                            $adeudo->checked = true;
                            $importe_acreditar = 0;
                        }
                    }
                }
            }
        }
        #endregion

        #region Se acreditan cargos sin importar fecha
        if($importe_acreditar > 0)
        {
            foreach ($prestamos as $item)
            {
                foreach ($item->tbl_adeudos as $adeudo) {
                    if($adeudo->tbl_cargo)
                    {
                        if($importe_acreditar >= $adeudo->tbl_cargo->importe_total)
                        {
                            $adeudo->tbl_cargo->acreditar = number_format($adeudo->tbl_cargo->importe_total, 2);
                            $adeudo->tbl_cargo->checked = true;
                            $importe_acreditar -= $adeudo->tbl_cargo->importe_total;
                        }
                        else if($importe_acreditar > 0)
                        {
                            $adeudo->tbl_cargo->acreditar = number_format($importe_acreditar, 2);
                            $adeudo->tbl_cargo->checked = true;
                            $importe_acreditar = 0;
                        }
                    }
                }
            }
        }
        #endregion


        #region Se acreditan cargos o adeudos sin importar nada
        if($importe_acreditar > 0)
        {
            foreach ($prestamos as $item)
            {
                foreach ($item->tbl_adeudos as $adeudo) {
                    if(!$adeudo->checked)
                    {
                        if($importe_acreditar >= $adeudo->importe_total)
                        {
                            $adeudo->acreditar = number_format($adeudo->importe_total, 2);
                            $adeudo->checked = true;
                            $importe_acreditar -= $adeudo->importe_total;
                        }
                        else if($importe_acreditar > 0)
                        {
                            $adeudo->acreditar = number_format($importe_acreditar, 2);
                            $adeudo->checked = true;
                            $importe_acreditar = 0;
                        }
                    }
                }
            }
        }

        #endregion

        return view('prestamos._pagos')->with(compact('prestamos'));
    }

    public function get_prestamos_by_cliente_id(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $cliente_id = $request->cliente_id;

            $prestamos = tbl_prestamos::get_list_vigentes_by_cliente_id($cliente_id);

            foreach ($prestamos as $item)
                $item->folio = $item->folio;

            return Response::json($prestamos);
        }

        return Response::json(null);
    }

    public function get_recibos_by_prestamo_id(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){

            $prestamos = tbl_adeudos::get_list_by_prestamo_id($request->prestamo_id);

            return Response::json($prestamos);
        }

        return Response::json(null);
    }
}
