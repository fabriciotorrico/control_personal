<section  id="content" style="background-color: #002640;">

    <div class="" >
        <div class="container">

            <div class="row">
              <div class="col-sm-6 col-sm-offset-3 myform-cont" >

                     <div class="myform-top">
                        <div class="myform-top-left">
                           <img  src="{{ url('img/logo_igm.jpeg') }}" class="img-responsive logo" />
                          <h3 class="text-muted">Registro de Personas</h3>
                            <p class="text-muted">Por favor ingrese sus datos personales</p>
                        </div>
                        <div class="myform-top-right">
                          <i class="fa fa-user"></i>
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

                    <div class="myform-bottom">

                    <form action="{{ url('crear_usuario') }}" method="post" id="f_crear_usuario" class="formentrada" >
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text">Nombres</label>
                                <input type="text" name="nombre" placeholder="Nombres" class="form-control" value="{{ old('nombre') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                    <label class="text">Apellido Paterno</label>
                                <input type="text" name="paterno" placeholder="Apellido Paterno" class="form-control" value="{{ old('paterno') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="text">Apellido Materno</label>
                                <input type="text" name="materno" placeholder="Apellido Materno" class="form-control" value="{{ old('materno') }}" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="text">Grado o Profesión</label>
                                <input type="text" name="grado" placeholder="Grado o Profesión" class="form-control" value="{{ old('grado') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="text">Cargo u Observación</label>
                                <input type="text" name="cargo" placeholder="Cargo u Observación" class="form-control" value="{{ old('cargo') }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text">Dependencia</label>
                                  <select class="form-control" name="id_depto">
                                    <option value="0">Sin Departamento (Administradores del Sistema)</option>
                                    @foreach ($deptos as $depto)
                                      <option value="{{$depto->id_depto}}">{{$depto->sigla}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="text">Cedula de Identidad</label>
                                <input type="text" class="form-control" name="cedula_identidad" placeholder="No. Carnet"  value="{{ old('cedula_identidad') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label class="text">Complemento</label>
                                <input type="text" class="form-control" name="complemento_cedula" placeholder="Si correpsonde"  value="{{ old('complemento_cedula') }}" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="text">Expedido</label>
                                <select class="form-control" name="expedido" required>
                                    <option value="LP">LP</option>
                                    <option value="OR">OR</option>
                                    <option value="PT">PT</option>
                                    <option value="CB">CB</option>
                                    <option value="SC">SC</option>
                                    <option value="BN">BN</option>
                                    <option value="PA">PA</option>
                                    <option value="TJ">TJ</option>
                                    <option value="CH">CH</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text">Telefono Celular</label>
                                <input type="number" name="telefono_celular" placeholder="Nro. de Celular" class="form-control"  value="{{ old('telefono_celular') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" class="form-control"  value="{{ old('fecha_nacimiento') }}" required>
                            </div>
                        </div>

                        <button type="submit" class="mybtn">Registrar</button>
                      </form>

                    </div>
              </div>
            </div>
        </div>
      </div>

</section>
