<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\estado_civil;
use App\Enums\estatus_cliente;
use App\Enums\movimiento_bitacora;
use App\Enums\sexo;
use App\Enums\tipos_documento;
use App\Enums\unidad_tiempo;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\AvalesRequest;
use App\Http\Requests\ConyugeAvalRequest;
use App\Http\Requests\DocumentosAvalRequest;
use App\Http\Requests\EconomiaAvalRequest;
use App\Http\Requests\InformacionContactoAvalRequest;
use App\Http\Requests\InformacionLaboralAvalRequest;
use App\tbl_avales;
use App\tbl_clientes;
use App\tbl_conyuge_aval;
use App\tbl_conyuge_cliente;
use App\tbl_documentos_aval;
use App\tbl_documentos_cliente;
use App\tbl_economia_aval;
use App\tbl_economia_cliente;
use App\tbl_estados;
use App\tbl_informacion_contacto_aval;
use App\tbl_informacion_contacto_cliente;
use App\tbl_informacion_laboral_aval;
use App\tbl_informacion_laboral_cliente;
use App\tbl_sucursales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Lang;
use Storage;
use Throwable;

class AvalesController extends Controller
{
    private $catalago_sistema = catalago_sistema::Avales;
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

        $model = tbl_avales::get_pagination($nombre, $sucursal_id, $domicilio, $perPage);

        if($request->ajax()){
            return view('avales._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        return view('avales.index')
            ->with(compact("nombre"))
            ->with(compact("sucursal_id"))
            ->with(compact("sucursales"))
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');

        $sexos = sexo::toSelectArray();
        $estados_civiles = estado_civil::toSelectArray();
        $unidades_tiempo = unidad_tiempo::toSelectArray();

        return view('avales.create')
            ->with(compact('sucursales'))
            ->with(compact('estados'))
            ->with(compact('sexos'))
            ->with(compact('unidades_tiempo'))
            ->with(compact('estados_civiles'));
    }

    public function create_post(AvalesRequest $request)
    {
        if(tbl_avales::check_if_exists($request->nombre, $request->apellido_paterno, $request->apellido_materno, 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', Lang::get('dictionary.message_already_exists_aval_name'));
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_avales($data_model);

        $foto = $request->foto;
        $ruta_foto = $foto ? HelperCrediuno::save_file($foto, 'public/avales_profile') : "";
        $model->foto = $ruta_foto;
        $model->fecha_nacimiento = $model->fecha_nacimiento ? Carbon::createFromFormat('d/m/Y', $model->fecha_nacimiento) : null;
        $model->es_cliente = false;
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_avales::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        if($model->estado_civil == estado_civil::Casado || $model->estado_civil == estado_civil::UnionLibre)
        {
            $datos_conyuge = new tbl_conyuge_aval();
            $datos_conyuge->aval_id = $model->aval_id;
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

            $response = tbl_conyuge_aval::create($datos_conyuge);

        }

        HelperCrediuno::save_bitacora($model->aval_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('avales.index');
    }

    public function details($id = 0)
    {
        $model = tbl_avales::get_by_id($id);
        if(!$model)
            abort(404);


        $model->edad = Carbon::parse($model->fecha_nacimiento)->age;
        $model->fecha_nacimiento = Carbon::parse($model->fecha_nacimiento)->format('d/m/Y');

        $tipos_documento = tipos_documento::toSelectArray();

        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');

        $sexos = sexo::toSelectArray();
        $estados_civiles = estado_civil::toSelectArray();
        $unidades_tiempo = unidad_tiempo::toSelectArray();

        return view('avales.details')
            ->with(compact('model'))
            ->with(compact('tipos_documento'))
            ->with(compact('sucursales'))
            ->with(compact('estados'))
            ->with(compact('sexos'))
            ->with(compact('unidades_tiempo'))
            ->with(compact('estados_civiles'));
    }

    #region Ajax
    public function get_tab_information()
    {
        $aval_id = request('aval_id');
        $tab = request('tab');

        switch ($tab)
        {
            case 'tab-documentos':
                $model = tbl_documentos_aval::get_list_by_aval_id($aval_id);
                return view('avales.views_details.documentos._documentos')
                    ->with(compact('model'));
                break;
            case 'tab-informacion-contacto':
                $model = tbl_informacion_contacto_aval::get_by_aval_id($aval_id);
                return view('avales.views_details.informacion_contacto._form')
                    ->with(compact('model'));
                break;
            case 'tab-informacion-laboral':
                $model = tbl_informacion_laboral_aval::get_by_aval_id($aval_id);
                $unidades_tiempo = unidad_tiempo::toSelectArray();
                $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
                return view('avales.views_details.informacion_laboral._form')
                    ->with(compact('unidades_tiempo'))
                    ->with(compact('estados'))
                    ->with(compact('model'));
                break;
            case 'tab-economia':
                $model = tbl_economia_aval::get_by_aval_id($aval_id);
                return view('avales.views_details.economia._form')
                    ->with(compact('model'));
                break;
            case 'tab-conyuge':
                $model = tbl_conyuge_aval::get_by_aval_id($aval_id);
                if($model)
                {
                    $model->fecha_nacimiento = $model->fecha_nacimiento != null ? Carbon::parse($model->fecha_nacimiento)->format('d/m/Y') : "";
                }
                return view('avales.views_details.conyuge._form')
                    ->with(compact('model'));
                break;
            default:
                return "<h1>En construccion</h1>";
                break;
        }
    }

    public function edit_datos_generales(AvalesRequest $request)
    {
        $aval_id = $request->aval_id;
        if(tbl_avales::check_if_exists($request->nombre, $request->apellido_paterno, $request->apellido_materno, $aval_id)){
            return Response::json(array(
                'Saved'     => false,
                'Message'   => Lang::get('dictionary.message_already_exists_aval_name')
            ));
        }

        $model = tbl_avales::get_by_id($aval_id);

        $foto = $request->foto;
        $ruta_foto = $foto ? HelperCrediuno::save_file($foto, 'public/avales_profile') : "";
        $model->foto = $foto ? $ruta_foto : $model->foto;
        $model->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento);
        $model->sucursal_id = $request->sucursal_id;
        $model->nombre = $request->nombre;
        $model->apellido_paterno = $request->apellido_paterno;
        $model->apellido_materno = $request->apellido_materno;
        $model->sexo = $request->sexo;
        $model->estado_civil = $request->estado_civil;
        $model->ocupacion = $request->ocupacion;
        $model->pais = $request->pais;
        $model->estado_id = $request->estado_id;
        $model->localidad = $request->localidad;
        $model->calle = $request->calle;
        $model->numero_exterior = $request->numero_exterior;
        $model->numero_interior = $request->numero_interior;
        $model->colonia = $request->colonia;
        $model->entre_calles = $request->entre_calles;
        $model->senas_particulares = $request->senas_particulares;
        $model->codigo_postal = $request->codigo_postal;
        $model->tiempo_residencia = $request->tiempo_residencia;
        $model->unidad_tiempo_residencia = $request->unidad_tiempo_residencia;

        $response = tbl_avales::edit($model);

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
        ));
    }

    public function manage_documentos(DocumentosAvalRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_documentos_aval($data_model);

            $model_original = tbl_documentos_aval::get_by_id($model->documento_aval_id);

            $file = $request->file;
            $ruta_file = $file ? HelperCrediuno::save_file($file, 'public/avales_documentos') : "";
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


            $response = $model_original ? tbl_documentos_aval::edit($model_original) : tbl_documentos_aval::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_documentos_aval::get_list_by_aval_id($model->aval_id);

            $html = view('avales.views_details.documentos._documentos')
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
        $documento_aval_id = request('documento_aval_id');

        $model = tbl_documentos_aval::get_by_id($documento_aval_id);

        $tipos_documento = tipos_documento::toSelectArray();
        return view('avales.views_details.documentos._form')
            ->with(compact('tipos_documento'))
            ->with(compact('model'));
    }

    public function manage_informacion_contacto(InformacionContactoAvalRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_informacion_contacto_aval($data_model);


            $model_original = tbl_informacion_contacto_aval::get_by_aval_id($model->aval_id);

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


            $response = $model_original ? tbl_informacion_contacto_aval::edit($model_original) : tbl_informacion_contacto_aval::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_informacion_contacto_aval::get_by_aval_id($model->aval_id);

            $html = view('avales.views_details.informacion_contacto._form')
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

    public function manage_informacion_laboral(InformacionLaboralAvalRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_informacion_laboral_aval($data_model);


            $model_original = tbl_informacion_laboral_aval::get_by_aval_id($model->aval_id);

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


            $response = $model_original ? tbl_informacion_laboral_aval::edit($model_original) : tbl_informacion_laboral_aval::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_informacion_laboral_aval::get_by_aval_id($model->aval_id);
            $unidades_tiempo = unidad_tiempo::toSelectArray();
            $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
            $html = view('avales.views_details.informacion_laboral._form')
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

    public function manage_economia(EconomiaAvalRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_economia_aval($data_model);


            $model_original = tbl_economia_aval::get_by_aval_id($model->aval_id);

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


            $response = $model_original ? tbl_economia_aval::edit($model_original) : tbl_economia_aval::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_economia_aval::get_by_aval_id($model->aval_id);

            $html = view('avales.views_details.economia._form')
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

    public function manage_conyuge(ConyugeAvalRequest $request)
    {
        if(request()->ajax())
        {
            $datetime_now = HelperCrediuno::get_datetime();
            $data_model = request()->except(['_token', '_method']);

            $model = new tbl_conyuge_aval($data_model);

            $model_original = tbl_conyuge_aval::get_by_id($model->conyuge_aval_id);


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


            $response = $model_original ? tbl_conyuge_aval::edit($model_original) : tbl_conyuge_aval::create($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved'     => false,
                    'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                ));
            }

            $model = tbl_conyuge_aval::get_by_aval_id($model->aval_id);

            $html = view('avales.views_details.conyuge._form')
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

    public function hacer_cliente(Request $request)
    {
        try{
            if(request()->ajax())
            {
                $datetime_now = HelperCrediuno::get_datetime();

                $model = tbl_avales::get_by_id($request->aval_id);

                if($model->es_cliente)
                {
                    return Response::json(array(
                        'Saved' => false,
                        'Message'   => Lang::get('dictionary.message_already_converter_cliente')
                    ));
                }

                $arr_aval = json_decode($model, true);
                $cliente = new tbl_clientes($arr_aval);

                $foto = $arr_aval['foto'];
                $ruta_foto = '';

                if(!empty($foto))
                {
                    $foto_explode = explode('/', $foto);
                    $nombre_foto = $foto_explode[(count($foto_explode) - 1)];
                    $extension = explode('.', $nombre_foto)[1];
                    $nombre = explode('-', $nombre_foto)[0];
                    $nuevo_nombre = $nombre.'-'.time().'.'.$extension;
                    $ruta_foto = 'public/clientes_profile/'.$nuevo_nombre;

                    Storage::copy($foto, $ruta_foto);
                }


                $cliente->foto = $ruta_foto;
                $cliente->sucursal_id = $model->sucursal_id;
                $cliente->medio_publicitario_id = 1;
                $cliente->grupo_id = $model->grupo_id;
                $cliente->estado_id = $model->estado_id;
                $cliente->fecha_nacimiento = Carbon::createFromFormat('Y-m-d', $model->fecha_nacimiento);
                $cliente->estatus = estatus_cliente::EnInvestigacion;
                $cliente->mostrar_cobranza = true;
                $cliente->limite_credito = 0;
                $cliente->es_aval = true;
                $cliente->aval_id = $request->aval_id;
                $cliente->activo = true;
                $cliente->creado_por = auth()->user()->id;
                $cliente->fecha_creacion = $datetime_now;

                $response = tbl_clientes::create($cliente);

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
                        $documento = new tbl_documentos_cliente($arr_item);

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

                        $documento->cliente_id = $cliente->cliente_id;
                        $documento->ruta = $ruta_archivo;
                        $documento->activo = true;
                        $documento->creado_por = auth()->user()->id;
                        $documento->fecha_creacion = $datetime_now;

                        $response = tbl_documentos_cliente::create($documento);
                        $i++;
                    }
                }
                #endregion

                #region informacion de contacto
                if(!empty($model->tbl_informacion_contacto))
                {
                    $arr_info_contacto = json_decode($model->tbl_informacion_contacto, true);
                    $info_contacto = new tbl_informacion_contacto_cliente($arr_info_contacto);

                    $info_contacto->cliente_id = $cliente->cliente_id;
                    $info_contacto->activo = true;
                    $info_contacto->creado_por = auth()->user()->id;
                    $info_contacto->fecha_creacion = $datetime_now;

                    $response = tbl_informacion_contacto_cliente::create($info_contacto);

                }
                #endregion

                #region informacion laboral
                if(!empty($model->tbl_informacion_laboral))
                {
                    $arr_info_laboral = json_decode($model->tbl_informacion_laboral, true);
                    $info_laboral = new tbl_informacion_laboral_cliente($arr_info_laboral);

                    $info_laboral->cliente_id = $cliente->cliente_id;
                    $info_laboral->estado_id = $model->tbl_informacion_laboral->estado_id;
                    $info_laboral->activo = true;
                    $info_laboral->creado_por = auth()->user()->id;
                    $info_laboral->fecha_creacion = $datetime_now;

                    $response = tbl_informacion_laboral_cliente::create($info_laboral);

                }
                #endregion

                #region economia
                if(!empty($model->tbl_economia))
                {
                    $arr_economia = json_decode($model->tbl_economia, true);
                    $info_economia = new tbl_economia_cliente($arr_economia);

                    $info_economia->cliente_id = $cliente->cliente_id;
                    $info_economia->activo = true;
                    $info_economia->creado_por = auth()->user()->id;
                    $info_economia->fecha_creacion = $datetime_now;

                    $response = tbl_economia_cliente::create($info_economia);

                }
                #endregion

                #region conyuge
                if(!empty($model->tbl_conyuge) && ($model->estado_civil == estado_civil::Casado || $model->estado_civil == estado_civil::UnionLibre))
                {
                    $arr_conyuge = json_decode($model->tbl_conyuge, true);
                    $info_conyuge = new tbl_conyuge_cliente($arr_conyuge);

                    $info_conyuge->cliente_id = $cliente->cliente_id;
                    $info_conyuge->fecha_nacimiento = $model->tbl_conyuge->fecha_nacimiento_conyuge != null ? Carbon::createFromFormat('Y-m-d', $model->tbl_conyuge->fecha_nacimiento_conyuge) : null;
                    $info_conyuge->activo = true;
                    $info_conyuge->creado_por = auth()->user()->id;
                    $info_conyuge->fecha_creacion = $datetime_now;

                    $response = tbl_conyuge_cliente::create($info_conyuge);

                }
                #endregion

                $model->cliente_id = $cliente->cliente_id;
                $model->es_cliente = true;

                $response = tbl_avales::edit($model);

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
    #endregion
}
