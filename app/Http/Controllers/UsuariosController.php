<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\User;
use Illuminate\Support\Facades\Validator;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;

use Auth;
use DateTime;

use App\Persona;

use App\Personal;
use App\Unidad;
use App\Cargo;
use App\Usada;
use App\Calificacion;
use App\Gestion;

class UsuariosController extends Controller
{

/*public function form_agregar_usuario(){
    //carga el formulario para agregar un nuevo usuario
    return view("formularios.form_agregar_usuario");
}*/

public function form_nuevo_usuario(){
    //Tomamos los deptos
    $deptos = \DB::table('partes_deptos')
              ->where('activo', 1)
              ->select('id_depto', 'sigla')
              ->get();

    return view("formularios.form_nuevo_usuario")->with("deptos", $deptos);
}

public function crear_usuario(Request $request){
  $fecha_actual = new DateTime(date('Y-m-d'));
  $fecha_actual = $fecha_actual->format('Y-m-d');

  \DB::table('personas')->insert([
      ['id_depto' => $request->id_depto,
       'cargo' => $request->cargo,
       'grado' => $request->grado,
       'nombre' => $request->nombre,
       'paterno' => $request->paterno,
       'materno' => $request->materno,
       'cedula_identidad' => $request->cedula_identidad,
       'complemento_cedula' => $request->complemento_cedula,
       'expedido' => $request->expedido,
       'telefono_celular' => $request->telefono_celular,
       'fecha_nacimiento' => $request->fecha_nacimiento,
       'fecha_registro' => $fecha_actual,
       'activo' => 1]
  ]);
  return view("mensajes.msj_usuario_creado")->with("msj","Persona agregada correctamente") ;
}

public function listado_usuarios(){
    //presenta un listado de usuarios paginados de 100 en 100
	$usuarios=User::paginate(100);
  $usuarios = \DB::table('personas')
            ->leftjoin('users', 'personas.id_persona', 'users.id_persona')
            ->where('personas.activo', 1)
            ->select('personas.grado', 'personas.nombre', 'personas.paterno', 'personas.materno', 'personas.cedula_identidad', 'personas.complemento_cedula',
                     'personas.expedido', 'personas.id_persona', 'users.id', 'users.name')
            ->get();
	return view("listados.listado_usuarios")->with("usuarios",$usuarios);
}

public function form_editar_usuario($id){
    //Tomamos el usuario con id_persona = $id (recibido)
    $usuario = \DB::table('users')
              ->where('id_persona', $id)
              ->where('activo', 1)
              ->first();

    //Si no existe un usuario para esa persona, lo creamos
    if ( $usuario == null ) {
      $fecha_actual = new DateTime(date('Y-m-d'));
      $fecha_actual = $fecha_actual->format('Y-m-d');
      //Tomamos los datos de la persona
      $personas = \DB::table('personas')
                ->where('id_persona', $id)
                ->where('activo', 1)
                ->get();
      foreach ($personas as $persona) {
        $user = $persona->paterno;
        $password= bcrypt( $persona->paterno );
      }

      \DB::table('users')->insert([
          ['name' => $user,
           'email' => $user,
           'password' => $password,
           'id_persona' => $id,
           'created_at' => $fecha_actual,
           'updated_at' => $fecha_actual,
           'activo' => 1]
      ]);
    }
    //Tomamos el usuario con id_persona = $id (recibido)
    $usuario = \DB::table('users')
              ->where('id_persona', $id)
              ->where('activo', 1)
              ->first();

    $usuario=User::find($usuario->id);
    $roles=Role::all();
    return view("formularios.form_editar_usuario")->with("usuario",$usuario)
	                                              ->with("roles",$roles);
}


public function form_borrado_usuario($id){
  //Tomamos el usuario con id_persona = $id (recibido)
  $usuarios = \DB::table('users')
            ->where('id_persona', $id)
            ->where('activo', 1)
            ->first();

  //Si existe el usuario, manda a borrar usuario
  if ($usuarios != null) {
    $usuario=User::find($usuarios->id);
    return view("confirmaciones.form_borrado_usuario")->with("usuario",$usuario);
  }
  else {
    //Si no existe, se da de baja a la persona
    //Tomamos los datos de la persona
    $personas = \DB::table('personas')
              ->where('id_persona', $id)
              ->where('activo', 1)
              ->get();
              //dd($personas);
    foreach ($personas as $persona) {
      $nombre_persona = $persona->paterno;
      $id_persona = $persona->id_persona;
    }
    //dd($personas);
    return view("confirmaciones.form_borrado_persona")
        ->with("nombre_persona",$nombre_persona)
        ->with("id_persona",$id_persona);
  }

}

public function borrar_usuario(Request $request){
    $idusuario=$request->input("id_usuario");
    $usuario=User::find($idusuario);

    if($usuario->delete()){
       return view("mensajes.msj_usuario_borrado")->with("msj","Usuario borrado correctamente") ;
    }
    else
    {
      return view("mensajes.mensaje_error")->with("msj","Hubo un error al borrar el usuario, intentarlo nuevamente.");
    }
}

public function borrar_persona(Request $request){
    $fecha_actual = new DateTime(date('Y-m-d'));
    $fecha_actual = $fecha_actual->format('Y-m-d');
    $id_persona = $request->id_persona;
    //Establecemos en activo 0 la persona
    \DB::table('personas')->where('id_persona', $id_persona)
    ->update(['activo' => 0]);

    return view("mensajes.msj_usuario_borrado")->with("msj","Persona dada de baja correctamente");
}


public function editar_acceso(Request $request){
    $idusuario=$request->input("id_usuario");
    $usuario=User::find($idusuario);

    if ($usuario->name != $request->name) {
        if( User::where('name', '=', $request->name)->exists()){
            return view("mensajes.mensaje_error")->with("msj","... El nombre de usuario ya se encuentra registrado en la base de datos...") ;
        }
    }

    $usuario->name=$request->input("name");
    $usuario->email=$request->input("password");
    $usuario->password= bcrypt( $request->input("password") );
    if( $usuario->save()){
        return view("mensajes.msj_usuario_actualizado")->with("msj","Usuario actualizado correctamente")->with("idusuario",$idusuario) ;
    }
    else
    {
        return view("mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ; intentarlo nuevamente ...") ;
    }
}

public function asignar_rol($idusu,$idrol){
        $usuario=User::find($idusu);
        $usuario->assignRole($idrol);
        $usuario=User::find($idusu);
        $rolesasignados=$usuario->getRoles();
        return json_encode ($rolesasignados);
}

public function quitar_rol($idusu,$idrol){
    $usuario=User::find($idusu);
    $usuario->revokeRole($idrol);
    $rolesasignados=$usuario->getRoles();
    return json_encode ($rolesasignados);
}





/*

public function form_nuevo_rol(){
    //carga el formulario para agregar un nuevo rol
    $roles=Role::all();
    return view("formularios.form_nuevo_rol")->with("roles",$roles);
}

public function form_nuevo_permiso(){
    //carga el formulario para agregar un nuevo permiso
     $roles=Role::all();
     $permisos=Permission::all();
    return view("formularios.form_nuevo_permiso")->with("roles",$roles)->with("permisos", $permisos);
}

public function crear_rol(Request $request){

    $reglas=[    'rol_nombre' => 'required|alpha',
                 'rol_slug' => 'required|unique:roles,slug',
                 'rol_descripcion' => 'required',
            ];

    $mensajes=[  'rol_nombre.alpha' => 'solo se permiten letras en el nombre, sin espacios , ni simbolos',
                 'rol_slug.unique' => 'el slug debe ser unico',
                 'rol_descripcion.required' => 'la descripcion es obligatoria',
            ];

    $validator = Validator::make( $request->all(),$reglas,$mensajes );
    if( $validator->fails() ){

        return new JsonResponse($validator->errors(), 422);
    }

   $rol=new Role;
   $rol->name=$request->input("rol_nombre") ;
   $rol->slug=$request->input("rol_slug") ;
   $rol->description=$request->input("rol_descripcion") ;
    if($rol->save())
    {
        return view("mensajes.msj_rol_creado")->with("msj","Rol agregado correctamente") ;
    }
    else
    {
        return view("mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
    }
}

public function crear_permiso(Request $request){

   $permiso=new Permission;
   $permiso->name=$request->input("permiso_nombre") ;
   $permiso->slug=$request->input("permiso_slug") ;
   $permiso->description=$request->input("permiso_descripcion") ;
    if($permiso->save())
    {
        return view("mensajes.msj_permiso_creado")->with("msj","Permiso creado correctamente") ;
    }
    else
    {
        return view("mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
    }
}

public function asignar_permiso(Request $request){

    $roleid=$request->input("rol_sel");
    $idper=$request->input("permiso_rol");
    $rol=Role::find($roleid);
    $rol->assignPermission($idper);

    if($rol->save())
    {
        return view("mensajes.msj_permiso_creado")->with("msj","Permiso asignado correctamente") ;
    }
    else
    {
        return view("mensajes.mensaje_error")->with("msj","...Hubo un error al agregar ;...") ;
    }
}

public function quitar_permiso($idrole,$idper){

    $role = Role::find($idrole);
    $role->revokePermission($idper);
    $role->save();

    return "ok";
}


public function borrar_rol($idrole){

    $role = Role::find($idrole);
    $role->delete();
    return "ok";
}

public function ObtieneUsuario($id_persona){
    $persona = Persona::find($id_persona);

    $ci = $persona->cedula_identidad.$persona->complemento_cedula;
    $numero = 0;
    $username = $ci;
    while (User::where('name', '=', $username)->exists()) { // user found
        $username=$username+$numero;
        $numero++;
    }

    //Quitar espacios en blanco
    $username = str_replace(' ', '', $username);
    return $username;
}

public function ObtieneUsuarioMd5 ($circ, $distrito, $recinto)
{
    $circ = $circ."-".$distrito."-".$recinto;
    $numero = 0;

    $exp_reg="[^A-Z0-9]";
    $longitud = 4;
    $codigo = substr(preg_replace($exp_reg, "", md5($circ)).preg_replace($exp_reg, "", md5($distrito)).preg_replace($exp_reg, "", md5($recinto)),
    0, $longitud);

    // $codigo = strtoupper(chr($i));
    $username = strtolower($circ."-".$codigo);

    while (User::where('name', '=', $username)->exists()) { // user found
        $username=$username.$numero;
        $numero++;
    }
    return $username;
}
*/
}
