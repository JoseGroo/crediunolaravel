<?php

namespace App\Http\Controllers;

use App\Enums\formas_pago;
use App\Helpers\HelperCrediuno;
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
        $formas_pago = formas_pago::getInstances();

        return view('formas_pago.index')
            ->with(compact("formas_pago"));
    }
}
