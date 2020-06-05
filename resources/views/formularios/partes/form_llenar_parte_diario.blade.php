@extends('layouts.app')

@section('htmlheader_title')
	Home
@endsection

@section('main-content')

<section  id="contenido_principal">
<div class="col-md-2">
</div>
<div class="col-md-8">
	<div class="myform-top">
		<div class="myform-top-left">
			<h3>Parte Diario - Departamento I</h3>
		</div>

	 </div>
    <div class="box-header with-border bg-primary">
        <h3 class="box-title">Fecha: <?php echo $fecha_actual; ?></h3>
    </div>

<div class="box-body box-white">
    <div class="table-responsive" >
	    <table  class="table table-hover table-striped" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Personal</th>
					<th>T</th>
					<th>TT</th>
					<th>P</th>
					<th>G</th>
					<th>BM</th>
					<th>P</th>
				</tr>
			</thead>
	    <tbody>
				@foreach ($datos as $key => $dato)
					<tr role="row" class="odd">
						<td>{{ $key + 1 }}</td>
			 			<td>{{ $dato->grado." ".$dato->paterno." ".$dato->materno." ".$dato->nombre }}</td>
					</tr>
			  @endforeach
			</tbody>
		</table>

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
