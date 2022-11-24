<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class movimiento_bitacora extends Enum implements LocalizedEnum
{
    const InicioSesion =   1;
    const CerroSesion =   2;
    const Edicion = 3;
    const CreoNuevoRegistro = 4;
    const Desactivado = 5;
    const Reactivado = 6;
    const Elimino = 7;
    const CambioContrasena = 8;
    const CanceloPrestamo = 9;
    const EntregoPrestamo = 10;
    const CanceloDescuento = 11;
    const GeneroCargoManual = 12;
    const AgregoClienteAGrupo = 13;
    const MarcoVistaNotasCliente = 14;
    const HizoTransferenciaRetiro = 15;
    const CanceloPrestamoCorte = 16;
    const CanceloPagoAdeudoCorte = 17;
    const CanceloCompraVentaDivisaCorte = 18;
    const CanceloCobroOtroConceptoCorte = 19;
    const CerroCorte = 20;
    const CanceloTransferenciaRetiroCorte = 21;
    const CanceloTransferenciaEntreCajasCorte = 22;
    const HizoTransferenciaEntreCajas = 23;
    const ReestructuraPrestamo = 24;
}
