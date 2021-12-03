<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class catalago_sistema extends Enum implements LocalizedEnum
{
    const Usuarios =   1;
    const Sucursales =   2;
    const Grupos = 3;
    const Intereses = 4;
    const Fondos = 5;
    const DiasFestivos = 6;
    const Contactos = 7;
    const MediosPublicitarios = 8;
    const Clientes = 9;
    const DocumentosCliente = 10;
    const ReferenciasCliente = 11;
    const Avales = 12;
    const Prestamos = 13;
    const Cortes = 14;
    const Descuentos = 15;
    const Adeudos = 16;
    const Divisas = 17;
}
