@extends('layouts.app')

@section('htmlheader_title')
	Home
@endsection
@section('main-content')
<section  id="contenido_principal">
<section  id="content">

    <div class="" >
        <div class="container">
            <div class="row">
              <div class="col-sm-6 col-sm-offset-3 myform-cont" >

                 <div class="myform-top">
                    <div class="myform-top-left">
                       {{-- <img  src="" class="img-responsive logo" /> --}}
                      <h3>Parte IGM</h3>
                        <p>Por favor complete los datos requeridos</p>
                    </div>
                    <div class="myform-top-right">
                      <i class="fa fa-edit"></i>
                    </div>
                  </div>

                  <div class="col-md-12" >
                    @if (count($errors) > 0)

                        <div class="alert alert-danger">
                            <strong>UPPS!</strong> Error al Registrar<br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
                   </div  >

                    <div id="div_notificacion_sol" class="myform-bottom">

                    <form action="{{ route('parte_igm') }}" method="post" class="" enctype="multipart/form-data" target="_blank">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
												<div class="content_p" id="content_p">
													<div class="form-group">
														<label >Fecha del reporte a generar</label>
														<input type="date" name="fecha" class="form-control" value="<?php echo $fecha_actual; ?>" required>
													</div>

													<div class="form-group">
														<label >Horario</label>
														<select name="id_horario" class="form-control">
															 @foreach($horarios as $horario)
															 	<option value="{{ $horario->id_horario }}">{{ $horario->horario }}</option>
															 @endforeach
														</select>
													</div>
												</div>

												<br>
                        <button type="submit" class="mybtn">Generar Reporte</button>
                      </form>

                    </div>
              </div>
            </div>
        </div>
      </div>

</section>
@endsection
