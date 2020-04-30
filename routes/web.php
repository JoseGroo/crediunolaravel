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

//region bitacora
Route::get('bitacora/', 'BitacoraController@index')->name('bitacora.index');
Route::get('bitacora/index', 'BitacoraController@index')->name('bitacora.index');
Route::get('bitacora/get_detalles_by_id', 'BitacoraController@get_detalles_by_id')->name('bitacora.get_detalles_by_id');
//endregion

//region auth
Route::get('auth/login', 'AuthController@login')->name('login');
Route::post('/auth/login_post', 'AuthController@loginPost')->name('login_post');
Route::post('/auth/logoff', 'AuthController@logoff')->name('system.logoff');
//endregion

Route::get('/', 'HomeController@index')->name('home');

