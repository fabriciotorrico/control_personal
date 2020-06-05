<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DateTime;

class PartesDiariosController extends Controller
{
    public function home_partes(){
        return view("formularios.home_partes");
    }

    public function form_llenar_parte_diario(){
        $fecha_actual = new DateTime(date('Y-m-d'));
        $fecha_actual = $fecha_actual->format('d-m-Y');
        $datos = \DB::table('personas')->orderBy('paterno', 'desc')->where('id_depto', Auth::user()->object_id)->where('activo', 1)->get();
        $estados = \DB::table('partes_estados')->where('activo', 1)->get();
        return view("formularios.partes.form_llenar_parte_diario", compact('datos'))
              ->with('estados', $estados)
              ->with('fecha_actual', $fecha_actual);
    }
}
