<?php

namespace App\Http\Controllers;

use App;
use App\Enums\archivos_descarga;
use App\Enums\catalago_sistema;
use App\Enums\estado_civil;
use App\Enums\estatus_adeudos;
use App\Enums\estatus_cliente;
use App\Enums\estatus_movimientos_corte;
use App\Enums\estatus_prestamo;
use App\Enums\formas_pago;
use App\Enums\movimiento_bitacora;
use App\Enums\sexo;
use App\Enums\tipo_nota;
use App\Enums\tipo_pago;
use App\Enums\tipos_documento;
use App\Enums\tipos_movimientos_corte;
use App\Enums\tipos_tarjeta;
use App\Enums\unidad_tiempo;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\ClientesRequest;
use App\Http\Requests\ConyugeRequest;
use App\Http\Requests\DocumentosClienteRequest;
use App\Http\Requests\EconomiaRequest;
use App\Http\Requests\HistorialClienteRequest;
use App\Http\Requests\InformacionContactoRequest;
use App\Http\Requests\InformacionLaboralRequest;
use App\Http\Requests\LimiteCreditoUpdateRequest;
use App\Http\Requests\NotaClienteRequest;
use App\Http\Requests\ReferenciasClienteRequest;
use App\tbl_adeudos;
use App\tbl_avales;
use App\tbl_cargos;
use App\tbl_cheques;
use App\tbl_clientes;
use App\tbl_conyuge_aval;
use App\tbl_conyuge_cliente;
use App\tbl_descuentos;
use App\tbl_documentos_aval;
use App\tbl_documentos_cliente;
use App\tbl_economia_aval;
use App\tbl_economia_cliente;
use App\tbl_estados;
use App\tbl_fichas_deposito;
use App\tbl_grupos;
use App\tbl_historial_cliente;
use App\tbl_informacion_contacto_aval;
use App\tbl_informacion_contacto_cliente;
use App\tbl_informacion_laboral_aval;
use App\tbl_informacion_laboral_cliente;
use App\tbl_medios_publicitarios;
use App\tbl_movimientos_corte;
use App\tbl_notas;
use App\tbl_notas_aviso;
use App\tbl_pagos;
use App\tbl_prestamos;
use App\tbl_referencias_cliente;
use App\tbl_sucursales;
use App\tbl_tarjetas;
use App\tbl_transferencias_electronicas;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Lang;
use Luecano\NumeroALetras\NumeroALetras;
use PHPUnit\TextUI\Help;


class ClientesController extends Controller
{
    private $catalago_sistema = catalago_sistema::Clientes;
    private $catalago_sistema_documentos = catalago_sistema::DocumentosCliente;
    private $catalago_sistema_referencias = catalago_sistema::DocumentosCliente;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $logged_user = auth()->user();
        $sucursal_id = 0;

         if($request->ajax())
         {
             $sucursal_id = request('sucursal_id');
         }else{
             $logged_user->sucursal_id;
         }

        $nombre = request('nombre');
        $domicilio = request('domicilio');
        $perPage = request('iPerPage') ?? 10;

        $model = tbl_clientes::get_pagination($nombre, $sucursal_id, $domicilio, $perPage);

        if($request->ajax()){
            return view('clientes._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        return view('clientes.index')
            ->with(compact("nombre"))
            ->with(compact("sucursal_id"))
            ->with(compact("sucursales"))
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function search()
    {
        $busqueda = request('nombre');
        $cliente = tbl_clientes::get_by_id($busqueda);

        if($cliente)
            return redirect()->route('clientes.details', $cliente->cliente_id);
        else
            return redirect()->route('clientes.index', ['nombre' => $busqueda]);
    }

    public function create()
    {
        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        $medios_publicitarios = tbl_medios_publicitarios::get_list()->pluck('medio_publicitario', 'medio_publicitario_id');
        $grupos = tbl_grupos::get_list()->pluck('grupo', 'grupo_id');
        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');

        $sexos = sexo::toSelectArray();
        $estados_civiles = estado_civil::toSelectArray();
        $unidades_tiempo = unidad_tiempo::toSelectArray();

        return view('clientes.create')
            ->with(compact('sucursales'))
            ->with(compact('medios_publicitarios'))
            ->with(compact('grupos'))
            ->with(compact('estados'))
            ->with(compact('sexos'))
            ->with(compact('unidades_tiempo'))
            ->with(compact('estados_civiles'));
    }

    public function create_post(ClientesRequest $request)
    {
        if(tbl_clientes::check_if_exists(request('nombre'), request('apellido_paterno'), request('apellido_materno'), 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', Lang::get('dictionary.message_already_exists_client_name'));
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_clientes($data_model);

        $foto = request('foto');
        $ruta_foto = $foto ? HelperCrediuno::save_file($foto, 'public/clientes_profile') : "";
        $model->foto = $ruta_foto;
        $model->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $model->fecha_nacimiento);
        $model->estatus = estatus_cliente::EnInvestigacion;
        $model->mostrar_cobranza = true;
        $model->limite_credito = 0;
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_clientes::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        if($model->estado_civil == estado_civil::Casado || $model->estado_civil == estado_civil::UnionLibre)
        {
            $datos_conyuge = new tbl_conyuge_cliente();
            $datos_conyuge->cliente_id = $model->cliente_id;
            $datos_conyuge->nombre = $request->nombre_conyuge;
            $datos_conyuge->apellido_paterno = $request->apellido_paterno_conyuge;
            $datos_conyuge->apellido_materno = $request->apellido_materno_conyuge;
            $datos_conyuge->fecha_nacimiento = $request->fecha_nacimiento_conyuge != null ? Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento_conyuge) : null;
            $datos_conyuge->telefono_movil = $request->telefono_movil_conyuge;
            $datos_conyuge->lugar_trabajo = $request->lugar_trabajo_conyuge;
            $datos_conyuge->puesto = $request->puesto_conyuge;
            $datos_conyuge->jefe = $request->jefe_conyuge;
            $datos_conyuge->activo = true;
            $datos_conyuge->creado_por = auth()->user()->id;
            $datos_conyuge->fecha_creacion = $datetime_now;

            $response = tbl_conyuge_cliente::create($datos_conyuge);
        }

        HelperCrediuno::save_bitacora($model->cliente_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('clientes.index');
    }

    public function details($id = 0)
    {
        $model = tbl_clientes::get_by_id($id);
        if(!$model)
            abort(404);

        $model->edad = Carbon::parse($model->fecha_nacimiento)->age;
        $model->fecha_nacimiento = Carbon::parse($model->fecha_nacimiento)->format('d/m/Y');

        $estatus = estatus_cliente::toSelectArray();
        $tipos_documento = tipos_documento::toSelectArray();

        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        $medios_publicitarios = tbl_medios_publicitarios::get_list()->pluck('medio_publicitario', 'medio_publicitario_id');
        $grupos = tbl_grupos::get_list()->pluck('grupo', 'grupo_id');
        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');

        $sexos = sexo::toSelectArray();
        $estados_civiles = estado_civil::toSelectArray();
        $unidades_tiempo = unidad_tiempo::toSelectArray();

        $notas_aviso = tbl_notas_aviso::get_list_by_cliente_id_no_visto($id);

        return view('clientes.details')
            ->with(compact('model'))
            ->with(compact('estatus'))
            ->with(compact('tipos_documento'))
            ->with(compact('sucursales'))
            ->with(compact('medios_publicitarios'))
            ->with(compact('grupos'))
            ->with(compact('estados'))
            ->with(compact('sexos'))
            ->with(compact('unidades_tiempo'))
            ->with(compact('notas_aviso'))
            ->with(compact('estados_civiles'));
    }

    public function pagos($id = 0)
    {

        HelperCrediuno::generar_cargos();
        $model = tbl_clientes::get_by_id($id);
        if(!$model)
            abort(404);

        $prestamos = tbl_prestamos::get_list_by_cliente_id_and_status($id, estatus_prestamo::Vigente);

        if(!auth()->user()->tiene_corte_abierto){
            \request()->session()->flash('message_prestamo', 'No tiene corte abierto para realizar esta operación.');
            return redirect()->route('clientes.details', $id);
        }

        /*if($prestamos->count() <= 0)
        {
            \request()->session()->flash('message_prestamo', 'No tiene pagos pendientes.');
            return redirect()->route('clientes.details', $id);
        }*/

        $formas_pago = formas_pago::toSelectArray();
        $tipos_tarjetas = tipos_tarjeta::toSelectArray();

        $pagos = session('pagos');
        $paga_con = session('paga_con');
        $cambio = session('cambio');

        return view('clientes.pagos')
            ->with(compact('model'))
            ->with(compact('formas_pago'))
            ->with(compact('tipos_tarjetas'))
            ->with(compact('paga_con'))
            ->with(compact('cambio'))
            ->with(compact('pagos'))
            ->with(compact('prestamos'));
    }

    public function pago_post(Request $request)
    {
        $datetime_now = HelperCrediuno::get_datetime();
        $cliente_id = $request->cliente_id;
        $corte_id = auth()->user()->corte_id;
        $adeudos_actuales = tbl_adeudos::get_list_by_cliente_id($cliente_id);
        $cargos_actuales = tbl_cargos::get_list_by_cliente_id($cliente_id);
        $total_pago = 0;
        //#region verificamos si se intenta hacer pago por adelantado
        $arr_adeudos_ids = [];
        foreach ($request->adeudos as $item) {
            if ($item['tipo'] != 'cargo')
                array_push($arr_adeudos_ids, $item['adeudo_id']);
            $total_pago += $item['abono'];
        }

        $adeudos_por_pagar = tbl_adeudos::get_list_by_ids($arr_adeudos_ids);

        foreach ($adeudos_por_pagar as $item)
        {
            $existentes = $adeudos_actuales->whereNotIn('adeudo_id', $arr_adeudos_ids)
                ->where('prestamo_id', $item->prestamo_id)
                ->where('adeudo_id', '<', $item->adeudo_id)->count();

            if($existentes > 0)
            {
                \request()->session()->flash('error_message', 'No se permite liquidar pagos por adelantado.');
                return redirect()->route('clientes.pagos', $cliente_id);
            }
        }
        //#endregion

        //Revisamos si tiene descuentos vigentes
        $descuento = null;
        $tbl_cheque = null;
        switch ($request->forma_pago)
        {
            case formas_pago::Descuento:
                $descuento = tbl_descuentos::get_vigente_by_cliente_id($cliente_id);
                if(!$descuento)
                {
                    \request()->session()->flash('error_message', 'El cliente no cuenta con descuento vigente.');
                    return redirect()->route('clientes.pagos', $cliente_id);
                }

                if($total_pago > $descuento->importe)
                {
                    \request()->session()->flash('error_message', 'El importe a pagar es mayor al descuento que tiene el cliente.');
                    return redirect()->route('clientes.pagos', $cliente_id);
                }
                break;
            case formas_pago::Cheque:
                $tbl_cheque = new tbl_cheques();
                $tbl_cheque->importe_cheque = $request->paga_con;
                $tbl_cheque->importe_pagado = $total_pago;
                $tbl_cheque->banco = $request->banco;
                $tbl_cheque->numero_cheque = $request->numero_cheque;
                $tbl_cheque->numero_cuenta = $request->numero_cuenta;
                $tbl_cheque->cliente_id = $cliente_id;
                $tbl_cheque->activo = true;
                $tbl_cheque->creado_por = auth()->user()->id;
                $tbl_cheque->fecha_creacion = $datetime_now;

                $response = tbl_cheques::create($tbl_cheque);
                break;
            case formas_pago::Tarjeta:
                $tbl_tarjeta = new tbl_tarjetas();
                $tbl_tarjeta->importe_pagado = $total_pago;
                $tbl_tarjeta->numero_tarjeta = $request->numero_tarjeta;
                $tbl_tarjeta->banco = $request->banco;
                $tbl_tarjeta->tipo = $request->tipo_tarjeta;
                $tbl_tarjeta->nombre_propietario = $request->nombre_propietario;
                $tbl_tarjeta->cliente_id = $cliente_id;
                $tbl_tarjeta->activo = true;
                $tbl_tarjeta->creado_por = auth()->user()->id;
                $tbl_tarjeta->fecha_creacion = $datetime_now;

                $response = tbl_tarjetas::create($tbl_tarjeta);
                break;
            case formas_pago::FichaDeposito:
                $tbl_ficha_deposito = new tbl_fichas_deposito();
                $tbl_ficha_deposito->importe_pagado = $total_pago;
                $tbl_ficha_deposito->importe_ficha_deposito = $request->paga_con;
                $tbl_ficha_deposito->cuenta_receptora = $request->cuenta_receptora;
                $tbl_ficha_deposito->banco = $request->banco;
                $tbl_ficha_deposito->cuentahabiente = $request->cuentahabiente;
                $tbl_ficha_deposito->cliente_id = $cliente_id;
                $tbl_ficha_deposito->activo = true;
                $tbl_ficha_deposito->creado_por = auth()->user()->id;
                $tbl_ficha_deposito->fecha_creacion = $datetime_now;

                $response = tbl_fichas_deposito::create($tbl_ficha_deposito);
                break;
            case formas_pago::TransferenciaElectronica:
                $tbl_transferencia = new tbl_transferencias_electronicas();
                $tbl_transferencia->importe_pagado = $total_pago;
                $tbl_transferencia->importe_transferencia = $request->paga_con;
                $tbl_transferencia->cuenta = $request->cuenta_receptora;
                $tbl_transferencia->banco = $request->banco;
                $tbl_transferencia->nombre_titular = $request->cuentahabiente;
                $tbl_transferencia->cliente_id = $cliente_id;
                $tbl_transferencia->activo = true;
                $tbl_transferencia->creado_por = auth()->user()->id;
                $tbl_transferencia->fecha_creacion = $datetime_now;

                $response = tbl_transferencias_electronicas::create($tbl_transferencia);
                break;
        }


        //#region Se realiza el pago
        $primer_pago = true;

        $lista_pagos = [];
        foreach ($request->adeudos as $item)
        {
            $adeudo = $adeudos_actuales->where('adeudo_id', $item['adeudo_id'])->first();
            $cargo = $cargos_actuales->where('cargo_id', $item['adeudo_id'])->first();
            $pago_liquidado = ($item['tipo'] != 'cargo' ? $adeudo->importe_total : $cargo->importe_total) == $item['abono'];

            $prestamo_id = $item['tipo'] != 'cargo' ? $adeudo->prestamo_id : $cargo->prestamo_id;

            $porcentaje_pago = ($item['abono'] * 100) / ($item['tipo'] != 'cargo' ? $adeudo->importe_total : $cargo->importe_total);

            //RESTARLE A CAPITAL
            $importe_capital = $item['tipo'] != 'cargo' ? $adeudo->capital : 0;
            $restar_importes_capital = !$pago_liquidado ? ($porcentaje_pago * $importe_capital) / 100 : $importe_capital;
            $importe_capital = $importe_capital - $restar_importes_capital;

            //RESTARLE A INTERES
            $importe_interes = $item['tipo'] != 'cargo' ? $adeudo->interes : $cargo->interes;
            $restar_importes_interes = !$pago_liquidado ? ($porcentaje_pago * $importe_interes) / 100 : $importe_interes;
            $importe_interes = $importe_interes - $restar_importes_interes;


            //RESTARLE A IVA
            $importe_iva = $item['tipo'] != 'cargo' ? $adeudo->iva : $cargo->iva;
            $restar_importes_iva = !$pago_liquidado ? ($porcentaje_pago * $importe_iva) / 100 : $importe_iva;
            $importe_iva = $importe_iva - $restar_importes_iva;

            $pago_actualizado = !$pago_liquidado ? $importe_capital + $importe_interes + $importe_iva : 0;


            $estatus_adeudo = $pago_liquidado ? estatus_adeudos::Liquidado : estatus_adeudos::Vigente;

            if($item['tipo'] != 'cargo')
            {
                $adeudo = tbl_adeudos::get_by_id($adeudo->adeudo_id);
                $adeudo->importe_total = $pago_actualizado;
                $adeudo->capital = $importe_capital;
                $adeudo->interes = $importe_interes;
                $adeudo->iva = $importe_iva;
                $adeudo->estatus = $estatus_adeudo;
                $response = tbl_adeudos::edit($adeudo);
            }else{
                $cargo = tbl_cargos::get_by_id($cargo->cargo_id);
                $cargo->importe_total = $pago_actualizado;
                $cargo->interes = $importe_interes;
                $cargo->iva = $importe_iva;
                $cargo->estatus = $estatus_adeudo;
                $response = tbl_cargos::edit_model($cargo);
            }

            if(!$response['saved'])
            {
                return redirect()->back()->withInput()
                    ->with('error',$response['error']);
            }

            $pago = new tbl_pagos();
            $pago->external_id = $item['tipo'] != 'cargo' ? $adeudo->adeudo_id : $cargo->cargo_id;
            $pago->tipo = $item['tipo'] != 'cargo' ? tipo_pago::Adeudo : tipo_pago::Cargo;
            $pago->metodo_pago = $request->forma_pago;
            $pago->descuento_id = $request->forma_pago == formas_pago::Descuento ? $descuento->descuento_id : null;
            $pago->capital = $restar_importes_capital;
            $pago->interes = $restar_importes_interes;
            $pago->iva = $restar_importes_iva;
            $pago->importe = $pago->capital + $pago->interes + $pago->iva;
            $formas_pago_para_cambio = collect(array(
                formas_pago::Cheque, formas_pago::FichaDeposito,
                formas_pago::TransferenciaElectronica,
                formas_pago::Tarjeta
            ));
            $pago->cambio = $formas_pago_para_cambio->contains($pago->metodo_pago) && $primer_pago ? $request->cambio : 0;
            $pago->activo = true;
            $pago->creado_por = auth()->user()->id;
            $pago->fecha_creacion = $datetime_now;

            $response = tbl_pagos::create($pago);
            array_push($lista_pagos, $pago);
            if(!$response['saved'])
            {
                return redirect()->back()->withInput()
                    ->with('error',$response['error']);
            }
            $primer_pago = false;
            $operacion = new tbl_movimientos_corte();
            $operacion->cliente_id = $cliente_id;
            $operacion->tipo = $item['tipo'] != 'cargo' ? tipos_movimientos_corte::PagosRecibos : tipos_movimientos_corte::PagosCargos;
            $operacion->importe = $pago->importe;
            $operacion->corte_id = $corte_id;
            $operacion->metodo_pago = $request->forma_pago;
            $operacion->external_id = $pago->pago_id;
            $operacion->estatus = estatus_movimientos_corte::Activo;
            $operacion->activo = true;
            $operacion->creado_por = auth()->user()->id;
            $operacion->fecha_creacion = $datetime_now;

            $response = tbl_movimientos_corte::create($operacion);
        }

        $prestamo_liquidado = tbl_prestamos::check_if_liquidado_by_prestamo_id($prestamo_id);
        if($prestamo_liquidado)
        {
            $prestamo = tbl_prestamos::get_by_id($prestamo_id);
            $prestamo->estatus = estatus_prestamo::Liquidado;
            $prestamo->fecha_liquidacion = $datetime_now;
            $response = tbl_prestamos::edit($prestamo);
        }

        if($request->forma_pago == formas_pago::Descuento)
        {
            $descuento->importe = $descuento->importe - $total_pago;
            $descuento->importe_acreditado += $total_pago;
            if($descuento->importe <= 0)
            {
                $descuento->estatus = App\Enums\estatus_descuentos::Acreditado;
            }
            $response = tbl_descuentos::edit($descuento);
        }
        //#endregion


        /*if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->cliente_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);*/

        \request()->session()->flash('pagos', $lista_pagos);
        \request()->session()->flash('paga_con', $request->paga_con);
        \request()->session()->flash('cambio', $request->cambio);
        return redirect()->route('clientes.pagos', $cliente_id);
    }

    public function download_pdf_pagos(Request $request){

        $pagos = tbl_pagos::get_list_by_ids($request->pagos_ids);
        $prestamos = tbl_prestamos::get_list_by_cliente_id_and_status($request->cliente_id, estatus_prestamo::Vigente);
        $saldo_anterior = $prestamos->sum('total_adeudo') + $pagos->sum('importe');
        $cliente = tbl_clientes::get_by_id($request->cliente_id);
        /*printf('Saldo anterior: ' . $saldo_anterior);
        dd('Saldo actual: ' . $prestamos->sum('total_adeudo'));*/
        /*printf($request->paga_con);
        printf($request->cambio);
        dd('');*/
        /*dd($pagos->first()->prestamo_id);*/

        $formatter = new NumeroALetras();
        $formatter->conector = '';
        $paga_con_letra =  $formatter->toInvoice($request->paga_con, 2, 'M.N.***');

        $data = [
            'pagos' => $pagos,
            'saldo_anterior' => $saldo_anterior,
            'saldo_actual' => $prestamos->sum('total_adeudo'),
            'paga_con' => $request->paga_con,
            'cambio' => $request->cambio,
            'cliente' => $cliente,
            'paga_con_letra' => $paga_con_letra,
            'user' => Auth::user()
        ];

        return HelperCrediuno::generate_pdf($data, 'clientes.pdfs.pdf_pagos', 'ticket-pago');
    }

    public function historial($id = 0)
    {
        $model = tbl_clientes::get_by_id($id);
        if(!$model)
            abort(404);

        $model->edad = Carbon::parse($model->fecha_nacimiento)->age;
        $model->fecha_nacimiento = Carbon::parse($model->fecha_nacimiento)->format('d/m/Y');


        return view('clientes.historial.historial')
            ->with(compact('model'));
    }

    public function estado_prestamo($id = 0)
    {
        $prestamo = tbl_prestamos::get_by_id($id);
        $model = tbl_clientes::get_by_id($prestamo->cliente_id);
        if(!$model)
            abort(404);

        $model->edad = Carbon::parse($model->fecha_nacimiento)->age;
        $model->fecha_nacimiento = Carbon::parse($model->fecha_nacimiento)->format('d/m/Y');

        return view('clientes.historial.estado_prestamo')
            ->with(compact('prestamo'))
            ->with(compact('model'));
    }

    #region Ajaxs
    public function edit_datos_generales(ClientesRequest $request)
    {
        $cliente_id = request('cliente_id');
        if(tbl_clientes::check_if_exists(request('nombre'), request('apellido_paterno'), request('apellido_materno'), $cliente_id)){
            return Response::json(array(
                'Saved'     => false,
                'Message'   => Lang::get('dictionary.message_already_exists_client_name')
            ));
        }

        $model = tbl_clientes::get_by_id($cliente_id);

        $foto = request('foto');
        $ruta_foto = $foto ? HelperCrediuno::save_file($foto, 'public/clientes_profile') : "";
        $model->foto = $foto ? $ruta_foto : $model->foto;
        $model->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', request('fecha_nacimiento'));
        $model->sucursal_id = request('sucursal_id');
        $model->medio_publicitario_id = request('medio_publicitario_id');
        $model->grupo_id = request('grupo_id');
        $model->nombre = request('nombre');
        $model->apellido_paterno = request('apellido_paterno');
        $model->apellido_materno = request('apellido_materno');
        $model->sexo = request('sexo');
        $model->estado_civil = request('estado_civil');
        $model->ocupacion = request('ocupacion');
        $model->pais = request('pais');
        $model->estado_id = request('estado_id');
        $model->localidad = request('localidad');
        $model->calle = request('calle');
        $model->numero_exterior = request('numero_exterior');
        $model->numero_interior = request('numero_interior');
        $model->colonia = request('colonia');
        $model->entre_calles = request('entre_calles');
        $model->senas_particulares = request('senas_particulares');
        $model->codigo_postal = request('codigo_postal');
        $model->tiempo_residencia = request('tiempo_residencia');
        $model->unidad_tiempo_residencia = request('unidad_tiempo_residencia');
        $model->vivienda = request('vivienda');
        $model->renta = request('renta');


        $response = tbl_clientes::edit($model);

        if(!$response['saved'])
        {
            return Response::json(array(
                'Saved'     => false,
                'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
            ));
        }

        //HelperCrediuno::save_bitacora($model->cliente_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        /*$model = tbl_clientes::get_by_id($cliente_id);
        $model->edad = Carbon::parse($model->fecha_nacimiento)->age;
        $html_cliente = view('clientes.views_details._datos_generales')
            ->with(compact('model'))
            ->render();*/

        //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
        return Response::json(array(
            'Saved'     => true,
            'Message'   => Lang::get('dictionary.message_save_correctly'),
            /*'Html'      => $html_cliente*/
        ));
    }

    public function edit_limite_credito(LimiteCreditoUpdateRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $cliente_id = request('cliente_id');
            $estatus = request('estatus');
            $limite_credito = request('limite_credito');
            $nota = trim(request('nota'));
            $mostrar_cobranza = request('mostrar_cobranza');

            $model = tbl_clientes::get_by_id($cliente_id);

            if(!$model)
                abort(404);

            if(auth()->user()->rol->rol == HelperCrediuno::$admin_gral_rol || auth()->user()->rol->rol == HelperCrediuno::$admin_rol)
            {
                $model->mostrar_cobranza = $mostrar_cobranza == 1 ? true : false;
            }
            $model->estatus = $estatus;
            $model->limite_credito = $limite_credito;

            $response = tbl_clientes::edit($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }


            if(!empty($nota))
            {

                $nota_model = new tbl_notas();
                $nota_model->nota = $nota;
                $nota_model->cliente_id = $cliente_id;
                $nota_model->tipo = auth()->user()->rol->rol == HelperCrediuno::$admin_gral_rol ? tipo_nota::Administrador : tipo_nota::Ventanilla;
                $nota_model->activo = true;
                $nota_model->creado_por = auth()->user()->id;
                $nota_model->fecha_creacion = $datetime_now;

                tbl_notas::create($nota_model);
            }

            $model = tbl_clientes::get_by_id($cliente_id);
            $model->edad = Carbon::parse($model->fecha_nacimiento)->age;
            $html_cliente = view('clientes.views_details._datos_generales')
                        ->with(compact('model'))
                        ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html_cliente
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function get_notas()
    {
        $cliente_id = request('cliente_id');
        $notas = tbl_notas::get_last_by_cliente_tipo_admin($cliente_id);

        return view('clientes.views_details._ultimas_notas')
            ->with(compact('notas'));
    }

    public function get_tab_information()
    {
        $cliente_id = request('cliente_id');
        $tab = request('tab');

        switch ($tab)
        {
            case 'tab-documentos':
                $model = tbl_documentos_cliente::get_list_buy_cliente_id($cliente_id);
                return view('clientes.views_details.documentos._documentos')
                    ->with(compact('model'));
                break;
            case 'tab-historial':
                $model = tbl_historial_cliente::get_by_cliente_id($cliente_id);
                return view('clientes.views_details.historial._form')
                    ->with(compact('model'));
                break;
            case 'tab-informacion-contacto':
                $model = tbl_informacion_contacto_cliente::get_by_cliente_id($cliente_id);
                return view('clientes.views_details.informacion_contacto._form')
                    ->with(compact('model'));
                break;
            case 'tab-informacion-laboral':
                $model = tbl_informacion_laboral_cliente::get_by_cliente_id($cliente_id);
                $unidades_tiempo = unidad_tiempo::toSelectArray();
                $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
                return view('clientes.views_details.informacion_laboral._form')
                    ->with(compact('unidades_tiempo'))
                    ->with(compact('estados'))
                    ->with(compact('model'));
                break;
            case 'tab-economia':
                $model = tbl_economia_cliente::get_by_cliente_id($cliente_id);
                return view('clientes.views_details.economia._form')
                    ->with(compact('model'));
                break;
            case 'tab-conyuge':
                $model = tbl_conyuge_cliente::get_by_cliente_id($cliente_id);
                if($model)
                {
                    $model->fecha_nacimiento = $model->fecha_nacimiento != null ? Carbon::parse($model->fecha_nacimiento)->format('d/m/Y') : "";
                }
                return view('clientes.views_details.conyuge._form')
                    ->with(compact('model'));
                break;
            case 'tab-referencias':
                $model = tbl_referencias_cliente::get_list_by_cliente_id($cliente_id);
                return view('clientes.views_details.referencias._referencias')
                    ->with(compact('model'));
                break;
            default:
                return "<h1>En construccion</h1>";
                break;
        }
    }

    public function manage_documentos(DocumentosClienteRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_documentos_cliente($data_model);

            $model_original = tbl_documentos_cliente::get_by_id($model->documento_cliente_id);

            $file = request('file');
            $ruta_file = $file ? HelperCrediuno::save_file($file, 'public/clientes_documentos') : "";
            $model->ruta = $file ? $ruta_file : $model_original->ruta;
            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {

                $model_original->documento = $model->documento;
                $model_original->ruta = $file ? $ruta_file : $model_original->ruta;
                $model_original->tipo = $model->tipo;
                $model_original->clave_identificacion = $model->clave_identificacion;
            }


            $response = $model_original ? tbl_documentos_cliente::edit($model_original) : tbl_documentos_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_documentos_cliente::get_list_buy_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.documentos._documentos')
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function get_form_documento()
    {
        $documento_cliente_id = request('documento_cliente_id');

        $model = tbl_documentos_cliente::get_by_id($documento_cliente_id);

        $tipos_documento = tipos_documento::toSelectArray();
        return view('clientes.views_details.documentos._form')
            ->with(compact('tipos_documento'))
            ->with(compact('model'));
    }



    public function delete_documento()
    {
        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));

        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $documento_cliente_id = request('documento_cliente_id');

            $model = tbl_documentos_cliente::get_by_id($documento_cliente_id);

            $model->activo = false;
            $response = tbl_documentos_cliente::edit($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_documentos_cliente::get_list_buy_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.documentos._documentos')
                ->with(compact('model'))
                ->render();

            HelperCrediuno::save_bitacora($documento_cliente_id, movimiento_bitacora::Elimino, $this->catalago_sistema_documentos, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function manage_referencias(ReferenciasClienteRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_referencias_cliente($data_model);

            $model_original = tbl_referencias_cliente::get_by_id($model->referencia_cliente_id);

            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {
                $model_original->nombre = $model->nombre;
                $model_original->apellido_paterno = $model->apellido_paterno;
                $model_original->apellido_materno = $model->apellido_materno;
                $model_original->telefono_fijo = $model->telefono_fijo;
                $model_original->telefono_movil = $model->telefono_movil;
                $model_original->telefono_oficina = $model->telefono_oficina;
                $model_original->correo_electronico = $model->correo_electronico;
                $model_original->calle = $model->calle;
                $model_original->numero_exterior = $model->numero_exterior;
                $model_original->colonia = $model->colonia;
                $model_original->tiempo_conocerlo = $model->tiempo_conocerlo;
                $model_original->unidad_tiempo_conocerlo = $model->unidad_tiempo_conocerlo;
                $model_original->relacion = $model->relacion;

            }


            $response = $model_original ? tbl_referencias_cliente::edit($model_original) : tbl_referencias_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_referencias_cliente::get_list_by_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.referencias._referencias')
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function manage_conyuge(ConyugeRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_conyuge_cliente($data_model);

            $model_original = tbl_conyuge_cliente::get_by_id($model->conyuge_cliente_id);


            $model->fecha_nacimiento = $model->fecha_nacimiento != null ? Carbon::createFromFormat('d/m/Y', $model->fecha_nacimiento) : null;
            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {
                $model_original->nombre = $model->nombre;
                $model_original->apellido_paterno = $model->apellido_paterno;
                $model_original->apellido_materno = $model->apellido_materno;
                $model_original->fecha_nacimiento = $model->fecha_nacimiento;
                $model_original->telefono_movil = $model->telefono_movil;
                $model_original->lugar_trabajo = $model->lugar_trabajo;
                $model_original->puesto = $model->puesto;
                $model_original->jefe = $model->jefe;

            }


            $response = $model_original ? tbl_conyuge_cliente::edit($model_original) : tbl_conyuge_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_conyuge_cliente::get_by_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.conyuge._form')
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function get_form_referencia_details()
    {
        $referencia_id = request('referencia_cliente_id');

        $model = tbl_referencias_cliente::get_by_id($referencia_id);

        return view('clientes.views_details.referencias._details')
            ->with(compact('model'));
    }

    public function get_form_referencia()
    {
        $referencia_id = request('referencia_cliente_id');

        $model = tbl_referencias_cliente::get_by_id($referencia_id);

        $unidades_tiempo = unidad_tiempo::toSelectArray();
        return view('clientes.views_details.referencias._form')
            ->with(compact('unidades_tiempo'))
            ->with(compact('model'));
    }

    public function delete_referencia()
    {
        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));

        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $referencia_id = request('referencia_cliente_id');

            $model = tbl_referencias_cliente::get_by_id($referencia_id);

            $model->activo = false;
            $response = tbl_referencias_cliente::edit($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_referencias_cliente::get_list_by_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.referencias._referencias')
                ->with(compact('model'))
                ->render();

            HelperCrediuno::save_bitacora($referencia_id, movimiento_bitacora::Elimino, $this->catalago_sistema_referencias, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function manage_historial(HistorialClienteRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_historial_cliente($data_model);


            $model_original = tbl_historial_cliente::get_by_cliente_id($model->cliente_id);

            $model->tiene_adeudo = request('tiene_adeudo') == 1 ? true : false;
            $model->esta_al_corriente = request('esta_al_corriente') == 1 ? true : false;
            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {

                $model_original->tiene_adeudo = $model->tiene_adeudo;
                $model_original->acreedor = $model->acreedor;
                $model_original->telefono = $model->telefono;
                $model_original->adeudo = $model->adeudo;
                $model_original->esta_al_corriente = $model->esta_al_corriente;
            }


            $response = $model_original ? tbl_historial_cliente::edit($model_original) : tbl_historial_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_historial_cliente::get_by_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.historial._form')
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function manage_informacion_contacto(InformacionContactoRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_informacion_contacto_cliente($data_model);


            $model_original = tbl_informacion_contacto_cliente::get_by_cliente_id($model->cliente_id);

            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {

                $model_original->telefono_fijo = $model->telefono_fijo;
                $model_original->telefono_movil = $model->telefono_movil;
                $model_original->telefono_alternativo_1 = $model->telefono_alternativo_1;
                $model_original->nombre_alternativo_1 = $model->nombre_alternativo_1;
                $model_original->parentesco_alternativo_1 = $model->parentesco_alternativo_1;
                $model_original->telefono_alternativo_2 = $model->telefono_alternativo_2;
                $model_original->nombre_alternativo_2 = $model->nombre_alternativo_2;
                $model_original->parentesco_alternativo_2 = $model->parentesco_alternativo_2;
                $model_original->correo_electronico = $model->correo_electronico;
            }


            $response = $model_original ? tbl_informacion_contacto_cliente::edit($model_original) : tbl_informacion_contacto_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_informacion_contacto_cliente::get_by_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.informacion_contacto._form')
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved'     => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function manage_informacion_laboral(InformacionLaboralRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_informacion_laboral_cliente($data_model);


            $model_original = tbl_informacion_laboral_cliente::get_by_cliente_id($model->cliente_id);

            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {
                $model_original->empresa = $model->empresa;
                $model_original->pais = $model->pais;
                $model_original->estado_id = $model->estado_id;
                $model_original->localidad = $model->localidad;
                $model_original->colonia = $model->colonia;
                $model_original->calle = $model->calle;
                $model_original->codigo_postal = $model->codigo_postal;
                $model_original->jefe_inmediato = $model->jefe_inmediato;
                $model_original->telefono = $model->telefono;
                $model_original->departamento = $model->departamento;
                $model_original->antiguedad = $model->antiguedad;
                $model_original->unidad_antiguedad = $model->unidad_antiguedad;
                $model_original->numero_exterior = $model->numero_exterior;
            }


            $response = $model_original ? tbl_informacion_laboral_cliente::edit($model_original) : tbl_informacion_laboral_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_informacion_laboral_cliente::get_by_cliente_id($model->cliente_id);
            $unidades_tiempo = unidad_tiempo::toSelectArray();
            $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
            $html = view('clientes.views_details.informacion_laboral._form')
                ->with(compact('unidades_tiempo'))
                ->with(compact('estados'))
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved'     => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function manage_economia(EconomiaRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_economia_cliente($data_model);


            $model_original = tbl_economia_cliente::get_by_cliente_id($model->cliente_id);

            $model->activo = true;
            $model->creado_por = $model_original->creado_por ?? auth()->user()->id;
            $model->fecha_creacion = $model->fecha_creacion ?? $datetime_now;

            if($model_original)
            {
                $model_original->ingresos_propios = $model->ingresos_propios;
                $model_original->ingresos_conyuge = $model->ingresos_conyuge;
                $model_original->otros_ingresos = $model->otros_ingresos;
                $model_original->gastos_fijos = $model->gastos_fijos;
                $model_original->gastos_eventuales = $model->gastos_eventuales;
            }


            $response = $model_original ? tbl_economia_cliente::edit($model_original) : tbl_economia_cliente::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_economia_cliente::get_by_cliente_id($model->cliente_id);

            $html = view('clientes.views_details.economia._form')
                ->with(compact('model'))
                ->render();

            //HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved'     => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function hacer_aval(Request $request)
    {
        try{
            if(request()->ajax())
            {
                $datetime_now = HelperCrediuno::get_datetime();

                $model = tbl_clientes::get_by_id($request->cliente_id);

                if($model->es_aval)
                {
                    return Response::json(array(
                        'Saved' => false,
                        'Message'   => Lang::get('dictionary.message_already_converted_aval')
                    ));
                }

                $arr_cliente = json_decode($model, true);
                $aval = new tbl_avales($arr_cliente);

                $foto = $arr_cliente['foto'];
                $ruta_foto = '';

                if(!empty($foto))
                {
                    $foto_explode = explode('/', $foto);
                    $nombre_foto = $foto_explode[(count($foto_explode) - 1)];
                    $extension = explode('.', $nombre_foto)[1];
                    $nombre = explode('-', $nombre_foto)[0];
                    $nuevo_nombre = $nombre.'-'.time().'.'.$extension;
                    $ruta_foto = 'public/avales_profile/'.$nuevo_nombre;

                    Storage::copy($foto, $ruta_foto);
                }


                $aval->foto = $ruta_foto;
                $aval->sucursal_id = $model->sucursal_id;
                $aval->estado_id = $model->estado_id;
                $aval->fecha_nacimiento = Carbon::createFromFormat('Y-m-d', $model->fecha_nacimiento);
                $aval->es_cliente = true;
                $aval->cliente_id = $request->cliente_id;
                $aval->activo = true;
                $aval->creado_por = auth()->user()->id;
                $aval->fecha_creacion = $datetime_now;

                $response = tbl_avales::create($aval);

                if(!$response['saved'])
                {
                    return Response::json(array(
                        'Saved'     => false,
                        'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                    ));
                }

                #region documentos
                if(!empty($model->tbl_documentos))
                {
                    $i = 0;
                    foreach ($model->tbl_documentos as $item)
                    {
                        $arr_item = json_decode($item, true);
                        $documento = new tbl_documentos_aval($arr_item);

                        $archivo = $arr_item['ruta'];
                        $ruta_archivo = '';

                        if(!empty($archivo))
                        {
                            $archivo_explode = explode('/', $archivo);
                            $nombre_archivo = $archivo_explode[(count($archivo_explode) - 1)];
                            $extension = explode('.', $nombre_archivo)[1];
                            $nombre = explode('-', $nombre_archivo)[0];
                            $nuevo_nombre = $nombre.'-'.time().$i.'.'.$extension;
                            $ruta_archivo = 'public/clientes_documentos/'.$nuevo_nombre;

                            Storage::copy($archivo, $ruta_archivo);
                        }

                        $documento->aval_id = $aval->aval_id;
                        $documento->ruta = $ruta_archivo;
                        $documento->activo = true;
                        $documento->creado_por = auth()->user()->id;
                        $documento->fecha_creacion = $datetime_now;

                        $response = tbl_documentos_aval::create($documento);
                        $i++;
                    }
                }
                #endregion

                #region informacion de contacto
                if(!empty($model->tbl_informacion_contacto))
                {
                    $arr_info_contacto = json_decode($model->tbl_informacion_contacto, true);
                    $info_contacto = new tbl_informacion_contacto_aval($arr_info_contacto);

                    $info_contacto->aval_id = $aval->aval_id;
                    $info_contacto->activo = true;
                    $info_contacto->creado_por = auth()->user()->id;
                    $info_contacto->fecha_creacion = $datetime_now;

                    $response = tbl_informacion_contacto_aval::create($info_contacto);
                }
                #endregion

                #region informacion laboral
                if(!empty($model->tbl_informacion_laboral))
                {
                    $arr_info_laboral = json_decode($model->tbl_informacion_laboral, true);
                    $info_laboral = new tbl_informacion_laboral_aval($arr_info_laboral);

                    $info_laboral->aval_id = $aval->aval_id;
                    $info_laboral->estado_id = $model->tbl_informacion_laboral->estado_id;
                    $info_laboral->activo = true;
                    $info_laboral->creado_por = auth()->user()->id;
                    $info_laboral->fecha_creacion = $datetime_now;

                    $response = tbl_informacion_laboral_aval::create($info_laboral);

                }
                #endregion

                #region economia
                if(!empty($model->tbl_economia))
                {
                    $arr_economia = json_decode($model->tbl_economia, true);
                    $info_economia = new tbl_economia_aval($arr_economia);

                    $info_economia->aval_id = $aval->aval_id;
                    $info_economia->activo = true;
                    $info_economia->creado_por = auth()->user()->id;
                    $info_economia->fecha_creacion = $datetime_now;

                    $response = tbl_economia_aval::create($info_economia);

                }
                #endregion

                #region conyuge
                if(!empty($model->tbl_conyuge) && ($model->estado_civil == estado_civil::Casado || $model->estado_civil == estado_civil::UnionLibre))
                {
                    $arr_conyuge = json_decode($model->tbl_conyuge, true);
                    $info_conyuge = new tbl_conyuge_aval($arr_conyuge);

                    $info_conyuge->aval_id = $aval->aval_id;
                    $info_conyuge->fecha_nacimiento = $model->tbl_conyuge->fecha_nacimiento_conyuge != null ? Carbon::createFromFormat('Y-m-d', $model->tbl_conyuge->fecha_nacimiento_conyuge) : null;
                    $info_conyuge->activo = true;
                    $info_conyuge->creado_por = auth()->user()->id;
                    $info_conyuge->fecha_creacion = $datetime_now;

                    $response = tbl_conyuge_aval::create($info_conyuge);

                }
                #endregion


                $model->aval_id = $aval->aval_id;
                $model->es_aval = true;

                $response = tbl_clientes::edit($model);


                return Response::json(array(
                    'Saved'     => true,
                    'Message'   => Lang::get('dictionary.message_save_correctly')
                ));
            }
        }catch (\Exception $e){
            //report($e);
            return Response::json(array(
                'Saved' => false,
                'Message'   => $e->getMessage()
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function autocomplete_cliente(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $cliente = $request->cliente;

            $clientes = tbl_clientes::get_list_by_search($cliente);

            return Response::json($clientes);
        }
    }

    public function autocomplete_cliente_html(Request $request)
    {
        $clientes = tbl_clientes::get_list_by_search_html($request->term);

        $clientes_response = $clientes->map(function ($item, $key) {
            return [
                'id' => $item->cliente_id,
                'text' => '#' . $item->cliente_id . ' - ' . $item->full_name,
                'html' => view('clientes._search_select2')
                    ->with(compact('item'))
                    ->render()

            ];
        });
        return Response::json($clientes_response);
    }

    public function get_tab_historial(Request $request)
    {
        $cliente_id = $request->cliente_id;
        $tab = $request->tab;

        switch ($tab)
        {
            case 'tab-notas-cliente':
                $model = tbl_notas::get_list_by_cliente_id($cliente_id);
                return view('clientes.historial._notas_cliente')
                    ->with(compact('model'));
                break;
            case 'tab-notas-aviso':
                $model = tbl_notas_aviso::get_list_by_cliente_id($cliente_id);
                return view('clientes.historial._notas_aviso')
                    ->with(compact('model'));
                break;
            case 'tab-prestamos-vigentes':
                $model = tbl_prestamos::get_list_by_cliente_id_and_status($cliente_id, estatus_prestamo::Vigente);
                return view('clientes.historial._prestamos')
                    ->with(compact('model'));
                break;
            case 'tab-prestamos-liquidados':
                $model = tbl_prestamos::get_list_by_cliente_id_and_status($cliente_id, estatus_prestamo::Liquidado);
                return view('clientes.historial._prestamos')
                    ->with(compact('model'));
                break;
            default:
                return "<h1>En construccion</h1>";
                break;
        }
    }

    public function nueva_nota_cliente_post(NotaClienteRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();

            $model = new tbl_notas();

            $model->nota = $request->nota;
            $model->cliente_id = $request->cliente_id;
            $model->tipo = tipo_nota::Ventanilla;
            $model->activo = true;
            $model->creado_por = auth()->user()->id;
            $model->fecha_creacion = $datetime_now;
            $response = tbl_notas::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model_list = tbl_notas::get_list_by_cliente_id($model->cliente_id);

            $html = view('clientes.historial._notas_cliente')
                ->with('model', $model_list)
                ->render();

            HelperCrediuno::save_bitacora($model->nota_id, movimiento_bitacora::CreoNuevoRegistro, catalago_sistema::NotaCliente, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function nueva_nota_aviso_post(NotaClienteRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();

            $model = new tbl_notas_aviso();

            $model->nota = $request->nota;
            $model->cliente_id = $request->cliente_id;
            $model->visto = false;
            $model->activo = true;
            $model->creado_por = auth()->user()->id;
            $model->fecha_creacion = $datetime_now;
            $response = tbl_notas_aviso::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model_list = tbl_notas_aviso::get_list_by_cliente_id($model->cliente_id);

            $html = view('clientes.historial._notas_aviso')
                ->with('model', $model_list)
                ->render();

            HelperCrediuno::save_bitacora($model->nota_id, movimiento_bitacora::CreoNuevoRegistro, catalago_sistema::NotaAviso, null, null);
            return Response::json(array(
                'Saved'     => true,
                'Message'   => Lang::get('dictionary.message_save_correctly'),
                'Html'      => $html
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }
    #endregion

    public function download_fie($archivo)
    {
        $file = storage_path('app/public');
        $content_type = "";
        switch ($archivo)
        {
            case archivos_descarga::SolicitudCliente:
                $file .= "/files/solicitud-prestamo.pdf";
                $content_type = "application/pdf";
                break;
            case archivos_descarga::AvisoCobro:
                $file .= "/files/aviso-de-cobro.docx";
                $content_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case archivos_descarga::Cuestionario:
                $file .= "/files/cuestionario.doc";
                $content_type = "application/msword";
                break;
            case archivos_descarga::HojaLogo:
                $file .= "/files/hoja-logo.docx";
                $content_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case archivos_descarga::FormatoIngresos:
                $file .= "/files/formato-ingresos.docx";
                $content_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case archivos_descarga::FormatoCorte:
                $file .= "/files/formato-corte-ventanilla.xlsx";
                $content_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
        }

        $headers = array(
            'Content-Type: '.$content_type,
        );
        return Response::download($file, basename($file), $headers);
    }
}
