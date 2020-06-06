@extends('layouts.app')

@section('htmlheader_title')
	Home
@endsection

@section('main-content')

<section  id="contenido_principal">
<div class="col-md-2">
</div>
<div class="col-md-8">
	@if(session()->has('mensaje_exito'))
		<div class="alert alert-success">
		{{ session()->get('mensaje_exito') }}
		</div>
	@endif
	@if(session()->has('mensaje_error'))
		<div class="alert alert-warning">
		{{ session()->get('mensaje_error') }}
		</div>
	@endif

	<div class="myform-top">
		<div class="myform-top-left">
		<h3>Parte Diario - {{$depto->sigla ?? ''}}</h3>
		</div>

	 </div>
    <div class="box-header with-border bg-primary">
        <h3 class="box-title">Fecha: <?php echo $fecha_actual; ?></h3>
    </div>

<div class="box-body box-white">
    <div class="table-responsive" >
		<form action="{{ url('crear_parte_diario') }}"  method="post">
	    <table  class="table table-hover table-striped" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Personal</th>
					@foreach ($estados as $item)
						<th>{{$item->abreviatura}}</th>
					@endforeach
				</tr>
			</thead>
	    <tbody>
				@foreach ($datos as $key => $dato)
					<tr role="row" class="odd">
						<td>{{ $key + 1 }}</td>
						 <td>{{ $dato->grado." ".$dato->paterno." ".$dato->materno." ".$dato->nombre }}</td>
						 @foreach ($estados as $item)
							<td><input type="radio" name="id_usuario_{{$dato->id_persona}}" class="[id_usuario-{{$dato->id_persona}}]" value="{{$item->id_estado}}"></td>{{-- T Trabajando --}}
						@endforeach
					</tr>
			  @endforeach
			</tbody>
		</table>
		{{-- <button type="submit" class="mybtn">Guardar</button> --}}
		<button type="submit" class="btn btn-block btn-primary">Guardar</button>
	</form>
	</div>
</div>

{{-- {{ $usuarios->links() }} --}}

@if(count($datos)==0)


<div class="box box-primary col-xs-12">

<div class='aprobado' style="margin-top:10px; text-align: center">

<label style='color:#177F6B'>
              ... No se encontró personal registrado ...
</label>

</div>

 </div>

@endif

</div></section>

@endsection

@section('scripts')

@parent
@endsection
