<?php

namespace App\Console\Commands;

use App\Helpers\HelperCrediuno;
use App\tbl_estados;
use App\tbl_medios_publicitarios;
use Illuminate\Console\Command;

class GenerarCargosDiarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generar_cargos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera cargos automaticamente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $datetime_now = HelperCrediuno::get_datetime();
        $count = tbl_estados::get_list()->count() + 1;
        $estado = new tbl_estados();
        $estado->estado = "Time: " . $datetime_now . " - " . $count;
        $estado->activo = true;
        $estado->save();
    }
}
