<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Partes\ParteDiario;
use App\Persona;
use Illuminate\Support\Facades\Auth;
use DateTime;

use Carbon\Carbon;

//use Fpdf;


class PartesDiariosController extends Controller
{
    public function home_partes(){
        return view("formularios.home_partes");
    }

    public function form_llenar_parte_diario(){
        $logueado  = Persona::find(Auth::user()->id_persona);

        $tiempo_actual = Carbon::now();

        $fecha_actual = $tiempo_actual->toDateString();

        $horario = \DB::table('partes_horarios')
        ->where('hora_desde', '<=', $tiempo_actual->toTimeString())
        ->where('hora_hasta', '>=', $tiempo_actual->toTimeString())
        ->where('activo', 1)->first();

        if ($horario == null) {
            return redirect('/home_partes')->with('mensaje_error', 'Disculpe, en este momento está fuera del horario de llenado de partes, por favor solicite su habilitación.');
        }

        //Tomamos los datos de las personas a mostrar en la lista (solo los del depto de la persona logeada)
        $datos = \DB::table('personas')->orderBy('paterno', 'asc')->where('id_depto', $logueado->id_depto)->where('activo', 1)->get();

        //Tomamos los registros de la tabla partes_diarios con la fecha y horario actual, para verificar si ya introdujo su parte.
        $parte_diario = ParteDiario::where('partes_diarios.fecha', $tiempo_actual->toDateString())
                ->leftjoin('personas', 'partes_diarios.id_persona', 'personas.id_persona')
                ->where('personas.id_depto', $logueado->id_depto)
                ->where('partes_diarios.activo', 1)
                ->select('partes_diarios.id_horario')
                ->get();

        $partes = 0;
        foreach ($parte_diario as $key => $value) {
            if ($value->id_horario == $horario->id_horario) {
                $partes ++;
            }
        }

        //Si existen registros (al menos 1) del parte para el horario y fecha actual para el depto del usuario logueado, mostramos el mensaje
        if ($partes > 0) {
            return redirect('/home_partes')->with('mensaje_error', 'Disculpe, ya envió el parte diario y está prohibida su midificación.');
        }

        /*if ($horario == null) {
            return redirect('/home_partes')->with('mensaje_error', 'Disculpe, está fuera del horario de llenado de partes, solicite su habilitación');
        }*/

        $depto = \DB::table('partes_deptos')->where('id_depto', $logueado->id_depto)->first();

        $estados = \DB::table('partes_estados')->where('activo', 1)->get();

        return view("formularios.partes.form_llenar_parte_diario", compact('datos'))
              ->with('depto', $depto)
              ->with('estados', $estados)
              ->with('fecha_actual', $fecha_actual);
    }


    public function crear_parte_diario(Request $request){
        // dd($request->all());

        $solicitud = $request->all();

        $logueado  = Persona::find(Auth::user()->id_persona);
        $datos = \DB::table('personas')->orderBy('paterno', 'asc')->where('id_depto', $logueado->id_depto)->where('activo', 1)->get();

        if (count($solicitud) != count($datos)) {
            return redirect('/form_llenar_parte_diario')->with('mensaje_error', 'Debe llenar la asistencia de todo el personal, no puede dejar opciones sin seleccionar.');
        }

        $tiempo_actual = Carbon::now();

        $horario = \DB::table('partes_horarios')
        ->where('hora_desde', '<=', $tiempo_actual->toTimeString())
        ->where('hora_hasta', '>=', $tiempo_actual->toTimeString())
        ->where('activo', 1)->first();

        if ($horario == null) {
            return redirect('/home_partes')->with('mensaje_error', 'Está fuera del horario de llenado o solicite su habilitación');
        }

        //Tomamos los registros de la tabla partes_diarios con la fecha y horario actual, para verificar si ya introdujo su parte.
        $parte_diario = ParteDiario::where('partes_diarios.fecha', $tiempo_actual->toDateString())
                ->leftjoin('personas', 'partes_diarios.id_persona', 'personas.id_persona')
                ->where('personas.id_depto', $logueado->id_depto)
                ->where('partes_diarios.activo', 1)
                ->select('partes_diarios.id_horario')
                ->get();

        $partes = 0;
        foreach ($parte_diario as $key => $value) {
            if ($value->id_horario == $horario->id_horario) {
                $partes ++;
            }
        }

        //Si existen registros (al menos 1) del parte para el horario y fecha actual para el depto del usuario logueado, mostramos el mensaje
        if ($partes > 0) {
            return redirect('/home_partes')->with('mensaje_error', 'Disculpe, ya envió el parte diario y está prohibida su midificación.');
        }

        $fecha_actual = new DateTime(date('Y-m-d'));

        foreach ($solicitud as $key => $value){
            //Separando id_usuario del request id_usuario_[]
            $id_persona = str_replace("id_usuario_", "", $key);
            \DB::table('partes_diarios')->insert([
                ['id_horario' => $horario->id_horario,
                 'id_persona' => $id_persona,
                 'fecha' => $fecha_actual,
                 'id_estado' => $value,
                 'id_registrador' => $logueado->id_persona,
                 'created_at' => $fecha_actual,
                 'updated_at' => $fecha_actual,
                 'activo' => 1]
            ]);
        }

        return redirect('/home_partes')->with('mensaje_exito', 'Información guardada y enviada correctamente');
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

   $pdf = \PDF::loadView('reportes.parte_por_departamento', compact('fecha', 'horario', 'deptos', 'partes', 'detalles'))
               ->setPaper('letter')
               ->stream('parte_por_departamento.pdf');
  return $pdf;
}


public function form_parte_individual(){
    //Tomamos la fecha actual
    $fecha_actual = new DateTime(date('Y-m-d'));
    $fecha_actual = $fecha_actual->format('Y-m-d');

    //Tomamos los horarios
    $horarios = \DB::table('partes_horarios')->get();

    //Tomamos el departamento del usuario logueado
    $logueado  = Persona::find(Auth::user()->id_persona);
    $deptos = \DB::table('partes_deptos')->where('id_depto', $logueado->id_depto)->get();

    //Retornamos la vista
    return view("formularios.partes.form_parte_individual")
          ->with('fecha_actual', $fecha_actual)
          ->with('horarios', $horarios)
          ->with('deptos', $deptos);
}

}
