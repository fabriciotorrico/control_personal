@extends('layouts.app')

@section('htmlheader_title')
	Home
@endsection

@section('main-content')

<section  id="contenido_principal">

<div class="box box-primary box-white">
     <div class="box-header">
        <h4 class="box-title">Usuarios</h4>
        <form   action="{{ url('buscar_usuario') }}"  method="post"  >
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			<!--div class="input-group input-group-sm">
				<input type="text" class="form-control" id="dato_buscado" name="dato_buscado" required>
				<span class="input-group-btn">
				<input type="submit" class="btn btn-primary" value="buscar" >
				</span>
			</div-->
        </form>

		<div class="margin" id="botones_control">
              <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="cargar_formulario(1);">Agregar Persona</a>
              <a href="{{ url("/listado_usuarios") }}"  class="btn btn-xs btn-primary" >Listado de Personas</a>
              <!--a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="cargar_formulario(2);">Roles</a>
              <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="cargar_formulario(3);" >Permisos</a-->
		</div>
    </div>

<div class="box-body box-white">

    <div class="table-responsive" >

	    <table  class="table table-hover table-striped" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Código</th>
					<th>Nombre</th>
					<th>Carnet</th>
					<th>Usuario</th>
					<th>Acción</th>
				</tr>
			</thead>
	    <tbody>
			  @foreach($usuarios as $usuario)
					<tr role="row" class="odd">
						<td>{{ $usuario->id_persona }}</td>
						<td class="mailbox-messages mailbox-name"><a href="javascript:void(0);"  style="display:block"><i class="fa fa-user"></i>&nbsp;&nbsp;{{ $usuario->grado." ".$usuario->paterno." ".$usuario->materno." ".$usuario->nombre }}</a></td>
						<td class="mailbox-messages mailbox-name"><a href="javascript:void(0);"  style="display:block"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;{{ $usuario->cedula_identidad." ".$usuario->complemento_cedula." ".$usuario->expedido }}</a></td>
						<td>{{ $usuario->name }}</td>
						<td>
							<button type="button" class="btn  btn-default btn-xs" onclick="verinfo_usuario({{  $usuario->id_persona }}, 1)" ><i class="fa fa-fw fa-edit"></i></button>
							<button type="button"  class="btn  btn-danger btn-xs"  onclick="borrado_usuario({{ $usuario->id_persona }});"  ><i class="fa fa-fw fa-remove"></i></button>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
	</div>
</div>





@if(count($usuarios)==0)


<div class="box box-primary col-xs-12">
	<div class='aprobado' style="margin-top:70px; text-align: center">
		<label style='color:#177F6B'>
      No existen personas registradas
		</label>
	</div>
</div>


@endif

</div></section>
@endsection
