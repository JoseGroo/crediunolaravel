<?php

use App\Enums\catalago_sistema;
use App\Enums\dias_semana;
use App\Enums\estado_civil;
use App\Enums\estatus_adeudos;
use App\Enums\estatus_cliente;
use App\Enums\estatus_descuentos;
use App\Enums\estatus_movimientos_corte;
use App\Enums\estatus_prestamo;
use App\Enums\formas_pago;
use App\Enums\movimiento_bitacora;
use App\Enums\periodos_prestamos;
use App\Enums\sexo;
use App\Enums\tipo_adeudo;
use App\Enums\tipo_fondo;
use App\Enums\tipo_intereses;
use App\Enums\tipo_nota;
use App\Enums\tipo_pago;
use App\Enums\tipos_documento;
use App\Enums\tipos_garantia;
use App\Enums\tipos_movimientos_corte;
use App\Enums\tipos_tarjeta;
use App\Enums\unidad_tiempo;

return [

    movimiento_bitacora::class => [
        movimiento_bitacora::InicioSesion => 'Inicio sesión',
        movimiento_bitacora::CerroSesion => 'Cerro sesión',
        movimiento_bitacora::Edicion => 'Edición',
        movimiento_bitacora::CreoNuevoRegistro => 'Creo nuevo registro',
        movimiento_bitacora::Desactivado => 'Desactivo',
        movimiento_bitacora::Reactivado => 'Reactivo',
        movimiento_bitacora::Elimino => 'Elimino',
        movimiento_bitacora::CambioContrasena => 'Cambio contraseña',
        movimiento_bitacora::CanceloPrestamo => 'Cancelo prestamo',
        movimiento_bitacora::EntregoPrestamo => 'Entrego prestamo',
        movimiento_bitacora::CanceloDescuento => 'Cancelo descuento',
        movimiento_bitacora::AgregoClienteAGrupo => 'Se agrego cliente a un grupo.'
    ],


    catalago_sistema::class => [
        catalago_sistema::Usuarios => 'Usuarios',
        catalago_sistema::Sucursales => 'Sucursales',
        catalago_sistema::Grupos => 'Grupos',
        catalago_sistema::Intereses => 'Intereses',
        catalago_sistema::Fondos => 'Fondos',
        catalago_sistema::DiasFestivos => 'Días festivos',
        catalago_sistema::Contactos => 'Contactos',
        catalago_sistema::MediosPublicitarios => 'Medios publicitarios',
        catalago_sistema::Clientes => 'Clientes',
        catalago_sistema::DocumentosCliente => 'Documento cliente',
        catalago_sistema::ReferenciasCliente => 'Referencia cliente',
        catalago_sistema::Avales => 'Avales',
        catalago_sistema::Prestamos => 'Prestamos',
        catalago_sistema::Cortes => 'Cortes',
        catalago_sistema::CobroOtrosConceptos => 'Cobro otros conceptos',
    ],


    tipo_fondo::class => [
        tipo_fondo::Bancario => 'Bancario',
        tipo_fondo::Consigna => 'De consigna',
    ],


    tipo_pago::class => [
        tipo_pago::Adeudo => 'Recibo',
        tipo_pago::Cargo => 'Cargo',
    ],

    estatus_cliente::class => [
        estatus_cliente::Acreditado => 'Acreditado',
        estatus_cliente::Desacreditado => 'Desacreditado',
        estatus_cliente::Trunco => 'Trunco',
        estatus_cliente::EnInvestigacion => 'En investigación',
        estatus_cliente::Juridico => 'Juridico',
        estatus_cliente::Incobrable => 'Incobrable',
        estatus_cliente::Rechazado => 'Rechazado',
    ],

    sexo::class => [
        sexo::Masculino => 'Masculino',
        sexo::Femenino => 'Femenino',
    ],

    estado_civil::class => [
        estado_civil::Soltero => 'Soltero',
        estado_civil::Casado => 'Casado',
        estado_civil::Viudo => 'Viudo',
        estado_civil::UnionLibre => 'Union libre',
        estado_civil::Divorciado => 'Divorciado',
        estado_civil::Separado => 'Separado',
    ],

    unidad_tiempo::class => [
        unidad_tiempo::Mes => 'Mes(es)',
        unidad_tiempo::Ano => 'Año(s)',
    ],

    tipo_nota::class => [
        tipo_nota::Administrador => 'Administrador',
        tipo_nota::Ventanilla => 'Ventanilla',
    ],

    tipos_documento::class => [
        tipos_documento::Identificacion => 'Identificación',
        tipos_documento::ComprobanteDomicilio => 'Comprobante de domicilio',
        tipos_documento::ComprobanteIngresos => 'Comprobante de ingresos',
        tipos_documento::Otro => 'Otro',
    ],

    periodos_prestamos::class => [
        periodos_prestamos::Diario => 'Diario',
        periodos_prestamos::Semanal => 'Semanal',
        periodos_prestamos::Quincenal => 'Quincenal',
        periodos_prestamos::Mensual => 'Mensual',
    ],

    dias_semana::class => [
        dias_semana::Domingo => 'Domingo',
        dias_semana::Lunes => 'Lunes',
        dias_semana::Martes => 'Martes',
        dias_semana::Miercoles => 'Miércoles',
        dias_semana::Jueves => 'Jueves',
        dias_semana::Viernes => 'Viernes',
        dias_semana::Sabado => 'Sábado',
    ],

    estatus_adeudos::class => [
        estatus_adeudos::Vigente => 'Vigente',
        estatus_adeudos::Liquidado => 'Liquidado',
    ],

    tipos_garantia::class => [
        tipos_garantia::DeVista => 'De vista',
        tipos_garantia::ConDocumentos => 'Con documentos',
        tipos_garantia::Vehicular => 'Vehicular',
        tipos_garantia::Empeno => 'Empeño',
    ],

    tipo_adeudo::class => [
        tipo_adeudo::Recibo => 'Recibo',
        tipo_adeudo::Capital=> 'Capital',
    ],

    estatus_prestamo::class => [
        estatus_prestamo::Generado => 'Generado',
        estatus_prestamo::Vigente => 'Vigente',
        estatus_prestamo::Liquidado => 'Liquidado',
        estatus_prestamo::Inactivo => 'Inactivo',
        estatus_prestamo::Cancelado => 'Cancelado',
    ],

    tipos_movimientos_corte::class => [
        tipos_movimientos_corte::EntregaPrestamo => 'Entrega de prestamo',
    ],

    formas_pago::class => [
        formas_pago::Efectivo => 'Efectivo',
        formas_pago::Cheque => 'Cheque',
        formas_pago::Tarjeta => 'Tarjeta de crédito/debito',
        formas_pago::Descuento => 'Descuento',
        formas_pago::FichaDeposito => 'Ficha de deposito',
        formas_pago::TransferenciaElectronica => 'Transferencia electronica',
        formas_pago::Refinanciar => 'Refinanciar',
        formas_pago::Retencion => 'Retención',
    ],

    estatus_movimientos_corte::class => [
        estatus_movimientos_corte::Activo => 'Activo',
        estatus_movimientos_corte::Cancelado => 'Cancelado',
    ],

    tipos_tarjeta::class => [
        tipos_tarjeta::Debito => 'Debito',
        tipos_tarjeta::Credito => 'Crédito',
    ],

    estatus_descuentos::class => [
        estatus_descuentos::Vigente => 'Vigente',
        estatus_descuentos::Vencido => 'Vencido',
        estatus_descuentos::Acreditado => 'Acreditado',
        estatus_descuentos::Cancelado => 'Cancelado',
    ]
];

