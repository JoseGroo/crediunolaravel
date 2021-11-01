<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('users/{name?}', 'UsersController@show')->name('users.list');

//region usuarios
Route::get('users/', 'UsersController@index')->name('users.index');
Route::get('users/index', 'UsersController@index')->name('users.index');
Route::get('users/create', 'UsersController@create')->name('users.create');
Route::post('users/create_post', 'UsersController@create_post')->name('users.create_post');
Route::get('users/{id?}/edit', 'UsersController@edit')->name('users.edit');
Route::post('users/edit_post', 'UsersController@edit_post')->name('users.edit_post');
Route::get('users/{id?}/details', 'UsersController@details')->name('users.details');
Route::post('users/delete', 'UsersController@delete')->name('users.delete');
Route::post('users/cambiar_contrasena', 'UsersController@cambiar_contrasena')->name('users.cambiar_contrasena');
//endregion

//region sucursales
Route::get('sucursales/', 'SucursalesController@index')->name('sucursales.index');
Route::get('sucursales/index', 'SucursalesController@index')->name('sucursales.index');
Route::get('sucursales/create', 'SucursalesController@create')->name('sucursales.create');
Route::post('sucursales/create_post', 'SucursalesController@create_post')->name('sucursales.create_post');
Route::get('sucursales/{id?}/edit', 'SucursalesController@edit')->name('sucursales.edit');
Route::post('sucursales/edit_post', 'SucursalesController@edit_post')->name('sucursales.edit_post');
Route::get('sucursales/{id?}/details', 'SucursalesController@details')->name('sucursales.details');
Route::post('sucursales/delete', 'SucursalesController@delete')->name('sucursales.delete');
Route::get('sucursales/get_ciudades_by_estado_id', 'SucursalesController@get_ciudades_by_estado_id')->name('sucursales.get_ciudades_by_estado_id');
//endregion

//region grupos
Route::get('grupos-cliente/', 'GruposClienteController@index')->name('grupos-cliente.index');
Route::get('grupos-cliente/index', 'GruposClienteController@index')->name('grupos-cliente.index');
Route::get('grupos-cliente/create', 'GruposClienteController@create')->name('grupos-cliente.create');
Route::post('grupos-cliente/create_post', 'GruposClienteController@create_post')->name('grupos-cliente.create_post');
Route::get('grupos-cliente/{id?}/edit', 'GruposClienteController@edit')->name('grupos-cliente.edit');
Route::post('grupos-cliente/edit_post', 'GruposClienteController@edit_post')->name('grupos-cliente.edit_post');
Route::get('grupos-cliente/{id?}/details', 'GruposClienteController@details')->name('grupos-cliente.details');
Route::post('grupos-cliente/delete', 'GruposClienteController@delete')->name('grupos-cliente.delete');

//endregion

//region intereses
Route::get('intereses/', 'InteresesController@index')->name('intereses.index');
Route::get('intereses/index', 'InteresesController@index')->name('intereses.index');
Route::get('intereses/{id?}/edit', 'InteresesController@edit')->name('intereses.edit');
Route::post('intereses/edit_post', 'InteresesController@edit_post')->name('intereses.edit_post');
Route::get('intereses/{id?}/details', 'InteresesController@details')->name('intereses.details');
//endregion


//region fondos
Route::get('fondos/', 'FondosController@index')->name('fondos.index');
Route::get('fondos/index', 'FondosController@index')->name('fondos.index');
Route::get('fondos/create', 'FondosController@create')->name('fondos.create');
Route::post('fondos/create_post', 'FondosController@create_post')->name('fondos.create_post');
Route::get('fondos/{id?}/edit', 'FondosController@edit')->name('fondos.edit');
Route::post('fondos/edit_post', 'FondosController@edit_post')->name('fondos.edit_post');
Route::get('fondos/{id?}/details', 'FondosController@details')->name('fondos.details');
Route::post('fondos/delete', 'FondosController@delete')->name('fondos.delete');
//endregion

//region dias festivos
Route::get('dias-festivos/', 'DiasFestivosController@index')->name('dias_festivos.index');
Route::get('dias-festivos/index', 'DiasFestivosController@index')->name('dias_festivos.index');
Route::get('dias-festivos/create', 'DiasFestivosController@create')->name('dias_festivos.create');
Route::post('dias-festivos/create_post', 'DiasFestivosController@create_post')->name('dias_festivos.create_post');
Route::get('dias-festivos/{id?}/edit', 'DiasFestivosController@edit')->name('dias_festivos.edit');
Route::post('dias-festivos/edit_post', 'DiasFestivosController@edit_post')->name('dias_festivos.edit_post');
Route::get('dias-festivos/{id?}/details', 'DiasFestivosController@details')->name('dias_festivos.details');
Route::post('dias-festivos/delete', 'DiasFestivosController@delete')->name('dias_festivos.delete');
//endregion

//region descuentos
Route::get('descuentos/', 'DescuentosController@index')->name('descuentos.index');
Route::get('descuentos/index', 'DescuentosController@index')->name('descuentos.index');
Route::get('descuentos/create', 'DescuentosController@create')->name('descuentos.create');
Route::post('descuentos/create_post', 'DescuentosController@create_post')->name('descuentos.create_post');
Route::get('descuentos/{id?}/edit', 'DescuentosController@edit')->name('descuentos.edit');
Route::post('descuentos/edit_post', 'DescuentosController@edit_post')->name('descuentos.edit_post');
Route::get('descuentos/{id?}/details', 'DescuentosController@details')->name('descuentos.details');
Route::post('descuentos/cancel', 'DescuentosController@cancel')->name('descuentos.cancel');
//endregion

//region contactos
Route::get('contactos/', 'ContactosController@index')->name('contactos.index');
Route::get('contactos/index', 'ContactosController@index')->name('contactos.index');
Route::get('contactos/create', 'ContactosController@create')->name('contactos.create');
Route::post('contactos/create_post', 'ContactosController@create_post')->name('contactos.create_post');
Route::get('contactos/{id?}/edit', 'ContactosController@edit')->name('contactos.edit');
Route::post('contactos/edit_post', 'ContactosController@edit_post')->name('contactos.edit_post');
Route::get('contactos/{id?}/details', 'ContactosController@details')->name('contactos.details');
Route::post('contactos/delete', 'ContactosController@delete')->name('contactos.delete');
//endregion

//region divisas
Route::get('divisas/', 'DivisasController@index')->name('divisas.index');
Route::get('divisas/index', 'DivisasController@index')->name('divisas.index');
Route::get('divisas/{id?}/edit', 'DivisasController@edit')->name('divisas.edit');
Route::post('divisas/edit_post', 'DivisasController@edit_post')->name('divisas.edit_post');
//endregion

//region medios publicitarios
Route::get('medios-publicitarios/', 'MediosPublicitariosController@index')->name('medios-publicitarios.index');
Route::get('medios-publicitarios/index', 'MediosPublicitariosController@index')->name('medios-publicitarios.index');
Route::get('medios-publicitarios/create', 'MediosPublicitariosController@create')->name('medios-publicitarios.create');
Route::post('medios-publicitarios/create_post', 'MediosPublicitariosController@create_post')->name('medios-publicitarios.create_post');
Route::get('medios-publicitarios/{id?}/edit', 'MediosPublicitariosController@edit')->name('medios-publicitarios.edit');
Route::post('medios-publicitarios/edit_post', 'MediosPublicitariosController@edit_post')->name('medios-publicitarios.edit_post');
Route::get('medios-publicitarios/{id?}/details', 'MediosPublicitariosController@details')->name('medios-publicitarios.details');
Route::post('medios-publicitarios/delete', 'MediosPublicitariosController@delete')->name('medios-publicitarios.delete');
//endregion

//region clientes
Route::get('clientes/', 'ClientesController@index')->name('clientes.index');
Route::get('clientes/index', 'ClientesController@index')->name('clientes.index');
Route::get('clientes/search', 'ClientesController@search')->name('clientes.search');
Route::get('clientes/create', 'ClientesController@create')->name('clientes.create');
Route::post('clientes/create_post', 'ClientesController@create_post')->name('clientes.create_post');
Route::get('clientes/{id?}/edit', 'ClientesController@edit')->name('clientes.edit');
Route::post('clientes/edit_post', 'ClientesController@edit_post')->name('clientes.edit_post');
Route::get('clientes/{id?}/details', 'ClientesController@details')->name('clientes.details');
Route::post('clientes/delete', 'ClientesController@delete')->name('clientes.delete');
Route::post('clientes/edit_limite_credito', 'ClientesController@edit_limite_credito')->name('clientes.edit_limite_credito');
Route::get('clientes/get_notas', 'ClientesController@get_notas')->name('clientes.get_notas');
Route::get('clientes/get_tab_information', 'ClientesController@get_tab_information')->name('clientes.get_tab_information');
Route::get('clientes/get_form_documento', 'ClientesController@get_form_documento')->name('clientes.get_form_documento');
Route::post('clientes/manage_documentos', 'ClientesController@manage_documentos')->name('clientes.manage_documentos');
Route::post('clientes/delete_documento', 'ClientesController@delete_documento')->name('clientes.delete_documento');
Route::get('clientes/get_form_referencia', 'ClientesController@get_form_referencia')->name('clientes.get_form_referencia');
Route::get('clientes/get_form_referencia_details', 'ClientesController@get_form_referencia_details')->name('clientes.get_form_referencia_details');
Route::post('clientes/manage_referencias', 'ClientesController@manage_referencias')->name('clientes.manage_referencias');
Route::post('clientes/delete_referencia', 'ClientesController@delete_referencia')->name('clientes.delete_referencia');
Route::post('clientes/manage_historial', 'ClientesController@manage_historial')->name('clientes.manage_historial');
Route::post('clientes/manage_informacion_contacto', 'ClientesController@manage_informacion_contacto')->name('clientes.manage_informacion_contacto');
Route::post('clientes/manage_informacion_laboral', 'ClientesController@manage_informacion_laboral')->name('clientes.manage_informacion_laboral');
Route::post('clientes/manage_economia', 'ClientesController@manage_economia')->name('clientes.manage_economia');
Route::post('clientes/manage_conyuge', 'ClientesController@manage_conyuge')->name('clientes.manage_conyuge');
Route::post('clientes/edit_datos_generales', 'ClientesController@edit_datos_generales')->name('clientes.edit_datos_generales');
Route::post('clientes/hacer_aval', 'ClientesController@hacer_aval')->name('clientes.hacer_aval');
Route::get('clientes/{id?}/pagos', 'ClientesController@pagos')->name('clientes.pagos');
Route::post('clientes/pago_post', 'ClientesController@pago_post')->name('clientes.pago_post');
Route::post('clientes/autocomplete_cliente', 'ClientesController@autocomplete_cliente')->name('clientes.autocomplete_cliente');
//endregion

//region avales
Route::get('avales/', 'AvalesController@index')->name('avales.index');
Route::get('avales/index', 'AvalesController@index')->name('avales.index');
Route::get('avales/create', 'AvalesController@create')->name('avales.create');
Route::post('avales/create_post', 'AvalesController@create_post')->name('avales.create_post');
Route::get('avales/{id?}/details', 'AvalesController@details')->name('avales.details');
Route::post('avales/edit_datos_generales', 'AvalesController@edit_datos_generales')->name('avales.edit_datos_generales');
Route::post('avales/manage_documentos', 'AvalesController@manage_documentos')->name('avales.manage_documentos');
Route::get('avales/get_tab_information', 'AvalesController@get_tab_information')->name('avales.get_tab_information');
Route::get('avales/get_form_documento', 'AvalesController@get_form_documento')->name('avales.get_form_documento');
Route::post('avales/manage_informacion_contacto', 'AvalesController@manage_informacion_contacto')->name('avales.manage_informacion_contacto');
Route::post('avales/manage_informacion_laboral', 'AvalesController@manage_informacion_laboral')->name('avales.manage_informacion_laboral');
Route::post('avales/manage_economia', 'AvalesController@manage_economia')->name('avales.manage_economia');
Route::post('avales/manage_conyuge', 'AvalesController@manage_conyuge')->name('avales.manage_conyuge');
Route::post('avales/hacer_cliente', 'AvalesController@hacer_cliente')->name('avales.hacer_cliente');
//endregion

//region prestamos
Route::get('prestamos/{id?}/generar', 'PrestamosController@generar')->name('prestamos.generar');
Route::post('prestamos/generar_post', 'PrestamosController@generar_post')->name('prestamos.generar_post');
Route::get('prestamos/{id?}/entregar', 'PrestamosController@entregar')->name('prestamos.entregar');
Route::post('prestamos/entregar_post', 'PrestamosController@entregar_post')->name('prestamos.entregar_post');
Route::get('prestamos/get_prestamo_by_id', 'PrestamosController@get_prestamo_by_id')->name('prestamos.get_prestamo_by_id');
Route::get('prestamos/get_list_avales_by_name', 'PrestamosController@get_list_avales_by_name')->name('prestamos.get_list_avales_by_name');
Route::get('prestamos/get_pagos_by_prestamo_id', 'PrestamosController@get_pagos_by_prestamo_id')->name('prestamos.get_pagos_by_prestamo_id');
Route::get('prestamos/get_pagos_acreditar_cantidad', 'PrestamosController@get_pagos_acreditar_cantidad')->name('prestamos.get_pagos_acreditar_cantidad');
Route::get('prestamos/get_prestamos_by_cliente_id', 'PrestamosController@get_prestamos_by_cliente_id')->name('prestamos.get_prestamos_by_cliente_id');
//endregion

//region adeudos
Route::get('pagos/', 'PagosController@index')->name('pagos.index');
Route::get('pagos/index', 'PagosController@index')->name('pagos.index');
Route::get('pagos/eliminar_cargos', 'PagosController@eliminar_cargos')->name('pagos.eliminar_cargos');
Route::post('pagos/eliminar_cargos_post', 'PagosController@eliminar_cargos_post')->name('pagos.eliminar_cargos_post');
Route::get('pagos/generar_manual', 'PagosController@generar_manual')->name('pagos.generar_manual');
//endregion

//region cortes
Route::post('cortes/create_post', 'CortesController@create_post')->name('cortes.create_post');
//endregion

//region bitacora
Route::get('bitacora/', 'BitacoraController@index')->name('bitacora.index');
Route::get('bitacora/index', 'BitacoraController@index')->name('bitacora.index');
Route::get('bitacora/get_detalles_by_id', 'BitacoraController@get_detalles_by_id')->name('bitacora.get_detalles_by_id');
//endregion

//region auth
Route::get('auth/login', 'AuthController@login')->name('login');
Route::post('/auth/login_post', 'AuthController@loginPost')->name('login_post');
Route::post('/auth/logoff', 'AuthController@logoff')->name('system.logoff');
Route::get('/auth/logoff', 'AuthController@logoff')->name('system.logoff');
//endregion

//region formas pago
Route::get('forma-pago/', 'FormasPagoController@index')->name('forma_pago.index');
Route::get('forma-pago/index', 'FormasPagoController@index')->name('forma_pago.index');
Route::get('forma-pago/get_list', 'FormasPagoController@get_list')->name('forma_pago.get_list');
//endregion

Route::get('/', 'HomeController@index')->name('home');




Route::get('/linkstorage', function () {

    $targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
    symlink($targetFolder,$linkFolder);
    echo 'Symlink process successfully completed';
});
