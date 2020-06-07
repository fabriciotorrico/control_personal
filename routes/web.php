<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use GuzzleHttp\Client;


Route::get('/', function () {
    return redirect('/login');
});

//REDIRECCIONA AL FORMULARIO DE CONSULTA DESDE UN INICIO

Route::get('/', function () {
    return redirect('home');
});

Route::group(['middleware' => 'auth'], function () {
    //Registro de partes
    Route::get('home_partes', 'PartesDiariosController@home_partes')->middleware('role:registrador');
    Route::get('form_llenar_parte_diario', 'PartesDiariosController@form_llenar_parte_diario')->middleware('role:registrador');
    Route::post('crear_parte_diario', 'PartesDiariosController@crear_parte_diario')->middleware('role:registrador');

    //Reportes
    Route::get('form_reportes', 'PartesDiariosController@form_reportes')->middleware('role:reporte');
    Route::post('form_parte_igm', 'PartesDiariosController@form_parte_igm')->middleware('role:reporte');
    Route::post('parte_igm', 'PartesDiariosController@parte_igm')->name('parte_igm')->middleware('role:reporte');
    Route::post('form_parte_por_departamento', 'PartesDiariosController@form_parte_por_departamento')->middleware('role:reporte');
    Route::post('parte_por_departamento', 'PartesDiariosController@parte_por_departamento')->name('parte_por_departamento');
    Route::get('form_parte_individual', 'PartesDiariosController@form_parte_individual');

    //Gestion Usuarios
    Route::get('/listado_usuarios', 'UsuariosController@listado_usuarios')->middleware('role:admin');
    Route::get('form_nuevo_usuario', 'UsuariosController@form_nuevo_usuario')->middleware('role:admin');
    Route::post('crear_usuario', 'UsuariosController@crear_usuario')->middleware('role:admin');
    Route::post('borrar_usuario', 'UsuariosController@borrar_usuario')->middleware('role:admin');
    Route::get('form_borrado_usuario/{idusu}', 'UsuariosController@form_borrado_usuario')->middleware('role:admin');
    Route::get('confirmacion_borrado_usuario/{idusuario}', 'UsuariosController@confirmacion_borrado_usuario')->middleware('role:admin');
    Route::post('borrar_persona', 'UsuariosController@borrar_persona')->middleware('role:admin');
    Route::post('editar_acceso', 'UsuariosController@editar_acceso')->middleware('role:admin');
    Route::get('form_editar_usuario/{id}', 'UsuariosController@form_editar_usuario')->middleware('role:admin');
    Route::get('asignar_rol/{idusu}/{idrol}', 'UsuariosController@asignar_rol')->middleware('role:admin');
    Route::get('quitar_rol/{idusu}/{idrol}', 'UsuariosController@quitar_rol')->middleware('role:admin');
});
