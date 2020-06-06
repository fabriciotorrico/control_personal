<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DateTime;
//use Fpdf;
//use Carbon;

class PartesDiariosController extends Controller
{
    public function home_partes(){
        return view("formularios.home_partes");
    }

    public function form_llenar_parte_diario(){
        $fecha_actual = new DateTime(date('Y-m-d'));
        $fecha_actual = $fecha_actual->format('d-m-Y');
        $datos = \DB::table('personas')->orderBy('paterno', 'asc')/*->where('id_depto', Auth::user()->object_id)*/->where('activo', 1)->get();
        $estados = \DB::table('partes_estados')->where('activo', 1)->get();
        return view("formularios.partes.form_llenar_parte_diario", compact('datos'))
              ->with('estados', $estados)
              ->with('fecha_actual', $fecha_actual);
    }

    public function form_reportes(){
        return view("formularios.partes.form_reportes");
    }

    public function form_parte_igm(){
        //Tomamos la fecha actual
        $fecha_actual = new DateTime(date('Y-m-d'));
        $fecha_actual = $fecha_actual->format('Y-m-d');

        //Tomamos los horarios
        $horarios = \DB::table('partes_horarios')->get();

        //Retornamos la vista
        return view("formularios.partes.form_parte_igm")
              ->with('fecha_actual', $fecha_actual)
              ->with('horarios', $horarios);
    }

    public function parte_igm(Request $request){
      $fecha = $request->fecha;
      $id_horario = $request->id_horario;

      $horarios = \DB::table('partes_horarios')
      ->where('id_horario', $id_horario)
      ->first();
      $horario = $horarios->horario." (De ".substr($horarios->hora_desde,0, 5)." a ".substr($horarios->hora_hasta,0, 5).")";

      //Tomamos los deptos
      $deptos = \DB::table('partes_deptos')
                ->where('partes_deptos.activo', 1)
                ->get();

      //Tomamos los registros de la fecha y horario seleccionados
      $partes = \DB::table('partes_diarios')
                ->leftjoin('personas', 'partes_diarios.id_persona', 'personas.id_persona')
                ->where('partes_diarios.activo', 1)
                ->where('partes_diarios.id_horario', $id_horario)
                ->where('partes_diarios.fecha', $fecha)
                ->select('partes_diarios.id_estado', 'personas.id_depto')
                ->get();

     $pdf = \PDF::loadView('reportes.parte_igm', compact('fecha', 'horario', 'deptos', 'partes'))
                 ->setPaper('letter')
                 ->stream('parte_igm.pdf');
    return $pdf;
  }

  public function form_parte_por_departamento(){
      //Tomamos la fecha actual
      $fecha_actual = new DateTime(date('Y-m-d'));
      $fecha_actual = $fecha_actual->format('Y-m-d');

      //Tomamos los horarios
      $horarios = \DB::table('partes_horarios')->get();

      //Tomamos los departamentos
      $deptos = \DB::table('partes_deptos')->get();

      //Retornamos la vista
      return view("formularios.partes.form_parte_por_departamento")
            ->with('fecha_actual', $fecha_actual)
            ->with('horarios', $horarios)
            ->with('deptos', $deptos);
  }

  public function parte_por_departamento(Request $request){
    $fecha = $request->fecha;
    $id_horario = $request->id_horario;
    $id_depto = $request->id_depto;

    $horarios = \DB::table('partes_horarios')
    ->where('id_horario', $id_horario)
    ->first();
    $horario = $horarios->horario." (De ".substr($horarios->hora_desde,0, 5)." a ".substr($horarios->hora_hasta,0, 5).")";

    //Tomamos el depto seleccionado
    $deptos = \DB::table('partes_deptos')
              ->where('partes_deptos.id_depto', $id_depto)
              ->get();

    //Tomamos los registros de la fecha y horario seleccionados
    $partes = \DB::table('partes_diarios')
              ->leftjoin('personas', 'partes_diarios.id_persona', 'personas.id_persona')
              ->where('partes_diarios.activo', 1)
              ->where('partes_diarios.id_horario', $id_horario)
              ->where('partes_diarios.fecha', $fecha)
              ->where('personas.id_depto', $id_depto)
              ->select('partes_diarios.id_estado', 'personas.id_depto', 'personas.grado', 'personas.paterno', 'personas.materno', 'personas.nombre')
              ->orderby('personas.paterno')
              ->get();

    //Tomamos los personas
    $detalles = \DB::table('personas')
              ->leftjoin('partes_diarios', 'partes_diarios.id_persona', 'personas.id_persona')
              ->where('personas.id_depto', $id_depto)
              ->where('personas.activo', 1)
              ->where('partes_diarios.id_horario', $id_horario)
              ->where('partes_diarios.fecha', $fecha)
              ->select('partes_diarios.id_estado', 'personas.id_depto', 'personas.grado', 'personas.paterno', 'personas.materno', 'personas.nombre')
              ->orderby('personas.paterno')
              ->get();

    //Tomamos las asistencias
    /*$personas = \DB::table('personas')
              ->select('personas.id_depto', 'personas.grado', 'personas.paterno', 'personas.materno', 'personas.nombre')
              ->where('personas.id_depto', $id_depto)
              ->where('personas.id_activo', 1)
              ->orderby('personas.paterno')
              ->get();*/

   $pdf = \PDF::loadView('reportes.parte_por_departamento', compact('fecha', 'horario', 'deptos', 'partes', 'detalles'))
               ->setPaper('letter')
               ->stream('parte_por_departamento.pdf');
  return $pdf;
}
}
