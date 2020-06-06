<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Partes\ParteDiario;
use App\Persona;
use Illuminate\Support\Facades\Auth;
use DateTime;
<<<<<<< HEAD
use Carbon\Carbon;
=======
//use Fpdf;
//use Carbon;
>>>>>>> b1e6fe831680d4ac6e15ec76639e68efeb2ea2a1

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
        ->where('desde', '<=', $tiempo_actual->toTimeString())
        ->where('hasta', '>=', $tiempo_actual->toTimeString())
        ->where('activo', 1)->first();

        if ($horario == null) {
            return redirect('/home_partes')->with('mensaje_error', 'Está fuera del horario de llenado o solicite su habilitación');
        }
        
        $datos = \DB::table('personas')->orderBy('paterno', 'asc')->where('id_depto', $logueado->id_depto)->where('activo', 1)->get();
        
        // $users = Topic::with('latestPost')->get()->sortByDesc('latestPost.created_at');
        // $datos = Persona::with('parte_diario')->get()->where('fecha', date('2020-06-05'));
        $parte_diario = ParteDiario::where('fecha', $tiempo_actual->toDateString())
                ->where('id_persona', $logueado->id_persona)
                ->where('activo', 1)
                ->get();

        $partes = 0;
        foreach ($parte_diario as $key => $value) {
            if ($value->id_horario == $horario->id_horario) {
                $partes ++;
            }
        }

        if ($partes > 0) {
            return redirect('/home_partes')->with('mensaje_error', 'Ya lleno el Parte diario');
        }
        
        if ($horario == null) {
            return redirect('/home_partes')->with('mensaje_error', 'Está fuera del horario de llenado o solicite su habilitación');
        }

        $depto = \DB::table('partes_deptos')->where('id_depto', $logueado->id_depto)->first();

        $estados = \DB::table('partes_estados')->where('activo', 1)->get();
        return view("formularios.partes.form_llenar_parte_diario", compact('datos'))
              ->with('depto', $depto)
              ->with('estados', $estados)
              ->with('fecha_actual', $fecha_actual);
    }

<<<<<<< HEAD
    public function crear_parte_diario(Request $request){
        // dd($request->all());

        $solicitud = $request->all();

        $logueado  = Persona::find(Auth::user()->id_persona);
        $datos = \DB::table('personas')->orderBy('paterno', 'asc')->where('id_depto', $logueado->id_depto)->where('activo', 1)->get();

        if (count($solicitud) != count($datos)) {
            return redirect('/form_llenar_parte_diario')->with('mensaje_error', 'Debe completar la información');
        }

        $tiempo_actual = Carbon::now();

        $horario = \DB::table('partes_horarios')
        ->where('desde', '<=', $tiempo_actual->toTimeString())
        ->where('hasta', '>=', $tiempo_actual->toTimeString())
        ->where('activo', 1)->first();

        if ($horario == null) {
            return redirect('/home_partes')->with('mensaje_error', 'Está fuera del horario de llenado o solicite su habilitación');
        }

        $parte_diario = ParteDiario::where('fecha', $tiempo_actual->toDateString())
        ->where('id_persona', $logueado->id_persona)
        ->where('activo', 1)
        ->get();

        $partes = 0;
        foreach ($parte_diario as $key => $value) {
            if ($value->id_horario == $horario->id_horario) {
                $partes ++;
            }
        }

        if ($partes > 0) {
            return redirect('/home_partes')->with('mensaje_error', 'Ya se llenó el Parte diario');
        }

        $fecha_actual = new DateTime(date('Y-m-d'));
        // $fecha_actual = $fecha_actual->format('d-m-Y');

        foreach ($solicitud as $key => $value){
            //Separando id_usuario del request id_usuario_[]
            $id_persona = str_replace("id_usuario_", "", $key);
            \DB::table('partes_diarios')->insert([
                ['id_horario' => $horario->id_horario,
                 'id_persona' => $id_persona,
                 'fecha' => $fecha_actual,
                 'id_estado' => $value,
                 'created_at' => $fecha_actual,
                 'updated_at' => $fecha_actual,
                 'activo' => 1]
            ]);
        }

        return redirect('/home_partes')->with('mensaje_exito', 'Información guardada correctamente');
    }
=======
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
>>>>>>> b1e6fe831680d4ac6e15ec76639e68efeb2ea2a1
}
