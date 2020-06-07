<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
  <ul class="nav navbar-nav">
    @role('admin')
      <li><a href="{{ url('listado_usuarios') }}"><i class="fa fa-user"> Gestión de Usuarios</i></a></li>
    @endrole

    @role('reporte')
      <li><a href="{{ url('form_reportes') }}"><i class="fa fa-file-pdf-o"></i> Reportes IGM</a></li>
    @endrole

    @role('registrador')
      <li><a href="{{ url('form_parte_individual') }}"><i class="fa fa-file-pdf-o"></i> Reportes Individuales</a></li>
    @endrole
  </ul>
</div>
