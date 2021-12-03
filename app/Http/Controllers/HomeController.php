<?php

namespace App\Http\Controllers;

use App\Helpers\HelperCrediuno;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $corte = session('corte');

        return view('home')
            ->with(compact("corte"));
    }

    public function download()
    {
        $data = [
            'titulo' => 'Hola soy una prueba'
        ];
        return HelperCrediuno::generate_pdf($data, 'test_pdf', 'home_test');
    }
}
