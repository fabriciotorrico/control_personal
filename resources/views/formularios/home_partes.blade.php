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
              <div class="col-sm-8 col-sm-offset-2 myform-cont" >

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
                       {{-- <img  src="" class="img-responsive logo" /> --}}
                      <h3>Sistema de Control de Personal	</h3>
                        <p>Por favor seleccione la acción que desa realizar</p>
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
                </div>


								<div class="row">
									<div class="col-md-12">
										<div class="myform-bottom">
											<form action="{{ url('form_llenar_parte_diario') }}"  method="get">
												<input type="hidden" name="" value="">
												<br>
												<button type="submit" style="font-size: 16px; padding: 30px;width: 100%; background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#87CEEB), color-stop(100%,#4682B4)); -webkit-box-shadow: inset 0px 0px 6px #fff; border-radius: 10px;">
													<div class="">
														<div class="">
															<span  style='font-size: 20px; color:black; height: 50px; font-weight:bold; text-align:center' class="">Llenar parte diario</span>
															<br>
														</div>
													</div>
												</button>
											</form>
										</div>
									</div>
								</div>

              </div>
            </div>

        </div>
      </div>

</section>

</section>
@endsection
