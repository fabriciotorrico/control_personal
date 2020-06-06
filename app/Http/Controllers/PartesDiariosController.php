<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Partes\ParteDiario;
use App\Persona;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Carbon\Carbon;

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
}
