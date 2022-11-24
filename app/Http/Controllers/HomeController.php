<?php

namespace App\Http\Controllers;

use App\Helpers\HelperCrediuno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Response;


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

    public function displayImage($filename)
    {
        $path = storage_public($filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
dd($path,$file,$type);
        return $response;
    }

    public function download()
    {
        $data = [
            'titulo' => 'Hola soy una prueba'
        ];
        return HelperCrediuno::generate_pdf($data, 'test_pdf', 'home_test');
    }
}
