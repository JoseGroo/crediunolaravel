<?php

namespace App\Http\Controllers;

use App\tbl_contactos;
use Carbon\Carbon;
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
    public function index()
    {
        $date = Carbon::now()->addDays(3);
        /*dd($date->dayOfWeek);
        dd($date->format('l'));*/

        return view('home');
    }
}
