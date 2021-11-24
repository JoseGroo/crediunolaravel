<?php

namespace App\Http\Controllers;

use App\Enums\formas_pago;
use App\Helpers\HelperCrediuno;
use App\tbl_cheques;
use App\tbl_fichas_deposito;
use App\tbl_tarjetas;
use App\tbl_transferencias_electronicas;
use Illuminate\Http\Request;

class FormasPagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $cheques = tbl_cheques::get_list();
        $ficha_deposito = tbl_fichas_deposito::get_list();
        $tarjetas = tbl_tarjetas::get_list();
        $transferencias_electronicas = tbl_transferencias_electronicas::get_list();

        return view('formas_pago.index')
            ->with(compact("transferencias_electronicas"))
            ->with(compact("tarjetas"))
            ->with(compact("ficha_deposito"))
            ->with(compact("cheques"));
    }
}
