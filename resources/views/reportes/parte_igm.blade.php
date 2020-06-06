<head>
  <meta charset="utf-8">
  <link href="{{ asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
  <p class="titulo"></p>
  <!-- ENCABEZADO -->
  <table style="width:100%; height:4%;">
    <tr>
      <th>
        <p class="titulo">PARTE DIARIO IGM</p>
        <p class="titulo">Fecha: {{ f_formato($fecha) }}</p>
        <p class="titulo">Horario: {{ $horario }}</p>
      </th>
    </tr>
  </table>

  <br>
  <table style="width:100%; height:4%;" border="1">
    <tr>
      <th rowspan="2">
        <p class="titulo">Departamento</p>
      </th>
      <th colspan="6">
        <p class="titulo">Estado</p>
      </th>
      <th rowspan="2">
        <p class="titulo">Total</p>
      </th>
    </tr>
    <tr>
      <th>
        <p class="titulo">T</p>
      </th>
      <th>
        <p class="titulo">TT</p>
      </th>
      <th>
        <p class="titulo">P</p>
      </th>
      <th>
        <p class="titulo">G</p>
      </th>
      <th>
        <p class="titulo">BM</p>
      </th>
      <th>
        <p class="titulo">PP</p>
      </th>
    </tr>
    <?php $total = 0;?>
    @foreach($deptos as $depto)
      <tr>
        <!--Calculamos los totales por tipo -->
        <?php
            $se_tomo_parte = 0;
            $total_t = $total_tt = $total_p= $total_g = $total_bm = $total_pp = 0;
        ?>
        @foreach($partes as $parte)
          <?php
            if ($parte->id_depto==$depto->id_depto) {
              //Registros con id_estado = T = 1
              if ($parte->id_estado==1) {$total_t = $total_t + 1;}
              //Registros con id_estado = TT = 2
              if ($parte->id_estado==2) {$total_tt = $total_tt + 1;}
              //Registros con id_estado = P = 3
              if ($parte->id_estado==3) {$total_p = $total_p + 1;}
              //Registros con id_estado = G = 4
              if ($parte->id_estado==4) {$total_g = $total_g + 1;}
              //Registros con id_estado = BM = 5
              if ($parte->id_estado==5) {$total_bm = $total_bm + 1;}
              //Registros con id_estado = PP = 6
              if ($parte->id_estado==6) {$total_pp = $total_pp + 1;}
              //Ademas, si entro al menos una vez, significa que el parte fue llenado
              $se_tomo_parte = 1;
            }
          ?>
        @endforeach
        <?php
          //Totales
          $total_depto = $total_t + $total_tt + $total_p + $total_g + $total_bm + $total_pp;
          $total = $total + $total_depto;
        ?>
        <?php
        //Si $se_tomo_parte = 0, pintamos la celda de rojo por que no se tomo parte.
        if ($se_tomo_parte == 0) {
          ?>
            <td style="background-color: #F5B7B1">
          <?php
        }
        else {
          ?>
            <td>
          <?php
        }?>
          {{ $depto->sigla }}
        </td>
        <td>
          <!--Registros con id_estado = T = 1-->
          <p class="texto_valores">{{ $total_t }}</p>
        </td>
        <td>
          <!--Registros con id_estado = TT = 2-->
          <p class="texto_valores">{{ $total_tt }}</p>
        </td>
        <td>
          <!--Registros con id_estado = P = 3-->
          <p class="texto_valores">{{ $total_p }}</p>
        </td>
        <td>
          <!--Registros con id_estado = G = 4-->
          <p class="texto_valores">{{ $total_g }}</p>
        </td>
        <td>
          <!--Registros con id_estado = BM = 5-->
          <p class="texto_valores">{{ $total_bm }}</p>
        </td>
        <td>
          <!--Registros con id_estado = PP = 6-->
          <p class="texto_valores">{{ $total_pp }}</p>
        </td>
        <td>
          <!--Sumatoria por departamento-->
          <p class="texto_valores">{{ $total_depto }}</p>
        </td>
      </tr>
    @endforeach
    <tr>
      <td colspan="7">
        <p class="texto_valores">TOTAL</p>
      </td>
      <td>
        <p class="texto_valores">{{ $total }}</p>
      </td>
    </tr>
  </table>
  <br>
  <table border="1">
    <tr>
      <th>Abreviación</th>
      <th>Descripción</th>
    </tr>
    <tr>
      <td><p class="texto_valores">T</p></td>
      <td><p class="texto_valores">Trabajando</p></td>
    </tr>
    <tr>
      <td><p class="texto_valores">TT</p></td>
      <td><p class="texto_valores">Teletrabajo</p></td>
    </tr>
    <tr>
      <td><p class="texto_valores">P</p></td>
      <td><p class="texto_valores">Patrullando</p></td>
    </tr>
    <tr>
      <td><p class="texto_valores">G</p></td>
      <td><p class="texto_valores">Guardia</p></td>
    </tr>
    <tr>
      <td><p class="texto_valores">BM</p></td>
      <td><p class="texto_valores">Baja Médica</p></td>
    </tr>
    <tr>
      <td><p class="texto_valores">PP</p></td>
      <td><p class="texto_valores">Permiso</p></td>
    </tr>
  </table>

</body>

<style>
  .titulo {font: bolt 20px cursive;
           margin: 5px;}

  .sangria {margin-left: 3%;
            margin-right: 3%;
            margin-top: 3px;}

  .texto_valores {font: 18px cursive;
          text-align: center;}

 .texto {font: 20px cursive;
         text-align: center;
          margin: 1px;
          margin-left: 3%;
          margin-right: 3%;
          margin-top: 3px;}

   .texto-instrucciones {font-size:12px;
                         vertical-align: bottom;}

   .texto-instrucciones-2 {font-size:12px;
                         vertical-align: bottom;
                         height:25;}

   .color-titulo {background-color: #C2D2D4;}

   .borde-marcado {border:1px solid black; border-bottom:0}
</style>
