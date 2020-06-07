$(document).ready(function(){

  if (document.getElementById("clock")) {

    var countDownDate = new Date("Oct 20, 2019 00:00:01").getTime();

    /* Update the count down every 1 second */
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();

      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the result in the element with id="clock"
      document.getElementById("clock").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s Restantes";

      // If the count down is finished, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("clock").innerHTML = "Día decisivo";
      }
    }, 1000);
  }

//
$('#btn_vaciar').click(function(){
  var div_resul="div_notificacion_sol";
  $.ajax({
    type: "POST",
    url: "truncate",
    data: {},
    success: function(resul)
    {
        if (resul == 'ok') {
          alertify.success('listo Bro!');
        }
    },
    error : function(xhr, status) {
        $("#"+div_resul+"").html('ha ocurrido un error al agregar el usuario, revise su conexion e intentelo nuevamente');
    }
  });
});


  $('#calendario_feriados').fullCalendar({
      header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,listYear'
      },
      // allDay : false,
      aspectRatio: 1,
      weekends: false,
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      selectable: true,
      selectHelper: true,
      eventTextColor: 'Black',

      eventRender: function(event, element) {
          element.bind('dblclick', function() {
              $('#btn_borrar_feriado').attr("disabled", false);
              $('#ModalEdit #id').val(event.id);
              $('#ModalEdit #title').val(event.title);
              $('#ModalEdit #color').val(event.color);
              $('#ModalEdit').modal('show');
              $('#desc_feriado').val(event.title);
          });
      },
      dayClick: function(date) {
        $('#ModalAdd #start').val(moment(date).format('YYYY-MM-DD'));
        $('#ModalAdd #end').val(moment(date).format('YYYY-MM-DD'));
        $('#ModalAdd').modal('show');
      },
      events: Eventos_iniciales,
      eventOverlap: false,
      // eventRender: false
  });

  //CALENDARIO
  $("#tablajson tbody").html("");
  $("#div_calendar").hide()

  $('#btn-calendar').click(function(){
    $("#div_calendar").show();
    $('#btn-pdf').show()
    $('#btn-cancelar').show()
    limpiar();
    var id_sol = $("#id_solicitud").val();
    calendario();

    $("#btn-calendar").hide();
  });


  $('#btn_guarda_fecha').click(function(){
    // $('#btn_guarda_fecha').attr("disabled", true);
  });

    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var f=new Date();
    // document.write(f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear());
    $("#hoy").text(f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear());

});

function refrescar(){
  timout=setTimeout(function(){
      location.reload();
  },3000,"JavaScript");//3 segundos
}

function recargar(){
  timout=setTimeout(function(){
      location.reload();
  },0,"JavaScript");//3 segundos
}

function refresh_calendar(){
  $('#btn_guarda_fecha').attr("disabled", false);
  var events = {
      url: 'calendar_datos',
      type: 'GET', // Send post data
      error: function() {
          alert('No se encontró ninguna fecha.');
      }
  };

  $('#calendar').fullCalendar('removeEventSource', events);
  $('#calendar').fullCalendar('addEventSource', events);
  $('#calendar').fullCalendar('refetchEvents');
}

function refresh_calendar_feriado(){
  $('#btn_guarda_fecha').attr("disabled", false);
  var events = {
      url: 'calendario_feriados',
      type: 'GET', // Send post data
      error: function() {
          alert('No se encontró ninguna fecha.');
      }
  };

  $('#calendario_feriados').fullCalendar('removeEventSource', events);
  $('#calendario_feriados').fullCalendar('addEventSource', events);
  $('#calendario_feriados').fullCalendar('refetchEvents');
}

function refresh_calendar_emergencias(id_sol){
  var events = {
      url: 'calendar_datos_emergencias/'+id_sol,
      type: 'GET', // Send post data
      error: function() {
          alert('No se encontró ninguna fecha.');
      }
  };

  $('#calendar_emergencias').fullCalendar('removeEventSource', events);
  $('#calendar_emergencias').fullCalendar('addEventSource', events);
  $('#calendar_emergencias').fullCalendar('refetchEvents');
}

function formato(fecha){
  return fecha.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
}

function limpiar(){
  // var id = $id_sol;
 $('#btn-calendar').attr("disabled", true);
  $.ajax({
      type:'get',
      url:"crear_sol",
      // data:{'id_sol':id},
      success: function(result){
        if (result == 'error') {
          alert("No se pudo realizar la petición");
        }
        else{
          $("#span_solicitud").text('Formulario de Solicitud No. '+result);
          estado_calendario(result);
          $("#id_solicitud").val(result);
          $("#id_solicitud_edit").val(result);
          // alert($("#id_solicitud").val());
        }
      }
  });
}


function verinfo_persona(id, form){
  var urlraiz=$("#url_raiz_proyecto").val();
  if(form == 1){var miurl =urlraiz+"/form_editar_persona/"+id+""; }
  if(form == 2){var miurl =urlraiz+"/form_baja_persona/"+id+""; }

	$("#capa_modal").show();
	$("#capa_formularios").show();
	var screenTop = $(document).scrollTop();
	$("#capa_formularios").css('top', screenTop);
  $("#capa_formularios").html($("#cargador_empresa").html());

    $.ajax({
    url: miurl
    }).done( function(resul)
    {
     $("#capa_formularios").html(resul);

    }).fail( function()
   {
    $("#capa_formularios").html('<span>...Ha ocurrido un error, revise su conexión y vuelva a intentarlo...</span>');
   }) ;
}

function  verinfo_usuario(id, form){
  var urlraiz=$("#url_raiz_proyecto").val();
  if(form == 1){var miurl =urlraiz+"/form_editar_usuario/"+id+""; }

	$("#capa_modal").show();
	$("#capa_formularios").show();
	var screenTop = $(document).scrollTop();
	$("#capa_formularios").css('top', screenTop);
  $("#capa_formularios").html($("#cargador_empresa").html());

    $.ajax({
    url: miurl
    }).done( function(resul)
    {
     $("#capa_formularios").html(resul);

    }).fail( function()
   {
    $("#capa_formularios").html('<span>...Ha ocurrido un error, revise su conexión y vuelva a intentarlo...</span>');
   }) ;
}

$(document).on("click",".div_modal", function(e){
	$(this).hide();
	$("#capa_formularios").hide();
	$("#capa_formularios").html("");
})

$(document).on("click","#cerrar_modal", function(e){
  $("#capa_modal").hide();
  $("#capa_formularios").hide();
})

document.onkeydown = function(evt) {
  evt = evt || window.event;
  if (evt.keyCode == 27) {
    $("#capa_modal").hide();
    $("#capa_formularios").hide();
  }
};

function cargar_formulario(arg){
   var urlraiz=$("#url_raiz_proyecto").val();
   $("#capa_modal").show();
   $("#capa_formularios").show();
   var screenTop = $(document).scrollTop();
   $("#capa_formularios").css('top', screenTop);
   $("#capa_formularios").html($("#cargador_empresa").html());
   if(arg==1){ var miurl=urlraiz+"/form_nuevo_usuario"; }
   if(arg==2){ var miurl=urlraiz+"/form_nuevo_rol"; }

    $.ajax({
    url: miurl
    }).done( function(resul)
    {
     $("#capa_formularios").html(resul);

    }).fail( function()
   {
    $("#capa_formularios").html('<span>...Ha ocurrido un error, revise su conexión y vuelva a intentarlo...</span>');
   }) ;

}

$(document).on("submit",".formentrada",function(e){

  var id_sol = $("#id_solicitud").val();
  e.preventDefault();
  $('#btn_guarda_fecha').attr("disabled", true);
  $('#ModalAdd').modal('hide');
  $('#ModalEdit').modal('hide');

  var quien=$(this).attr("id");
  var formu=$(this);
  var varurl="";
  if(quien=="f_enviar_agregar_persona"){  var varurl=$(this).attr("action");  var div_resul="div_notificacion_sol";}
  if(quien=="f_enviar_editar_persona"){  var varurl=$(this).attr("action");  var div_resul="div_notificacion_sol";}
  if(quien=="f_baja_persona"){  var varurl=$(this).attr("action");  var div_resul="div_notificacion_sol";}

  if(quien=="f_editar_solicitud"){  var varurl=$(this).attr("action");  var div_resul="div_notificacion_sol";}
  if(quien=="f_crear_usuario"){  var varurl=$(this).attr("action");  var div_resul="capa_formularios";  }
  if(quien=="f_crear_permiso"){  var varurl=$(this).attr("action");  var div_resul="capa_formularios";  }
  if(quien=="f_editar_usuario"){  var varurl=$(this).attr("action");  var div_resul="notificacion_E2";  }
  if(quien=="f_editar_acceso"){  var varurl=$(this).attr("action");  var div_resul="notificacion_E3";  }
  if(quien=="f_borrar_usuario"){  var varurl=$(this).attr("action");  var div_resul="capa_formularios";  }
  if(quien=="f_asignar_permiso"){  var varurl=$(this).attr("action");  var div_resul="capa_formularios";  }

  // $("#"+div_resul+"").html( $("#cargador_empresa").html());

  $.ajax({
    // la URL para la petición
    url : varurl,
    data : formu.serialize(),
    type : 'POST',
    dataType : 'html',

    success : function(resul) {

      if(quien=="f_baja_persona"){
        if (resul == 'ok') {
          recargar();
        }
        else if(resul == 'failed'){
          $("#"+div_resul+"").html('ha ocurrido un error, revise su conexion e intentelo nuevamente');
        }
      }else if(quien=="f_enviar_agregar_persona" || quien=="f_enviar_editar_persona"){
        if (resul == 'failed') {
          alertify.success('Ocurrió un error, revise su conexión');
        }else if(resul == 'apellido'){
          alertify.error('Debe ingresar al menos un apellido');
        }else{
          $("#"+div_resul+"").html(resul);
        }

      }
      else{
        // $('#capa_modal').modal('hide');
        $("#"+div_resul+"").html(resul);
      }

       },
    error : function(xhr, status) {
          $("#"+div_resul+"").html('ha ocurrido un error, revise su conexion e intentelo nuevamente');
    }
  });
})

$(document).on("submit",".form_crear_rol",function(e){
  e.preventDefault();
  var quien=$(this).attr("id");
  var formu=$(this);
  var varurl=$(this).attr("action");

   $("#div_notificacion_rol").html( $("#cargador_empresa").html());
   $(".form-group").removeClass("has-error");
   $(".help-block").text('');

  $.ajax({
    // la URL para la petición
    url : varurl,
    data : formu.serialize(),
    type : 'POST',
    dataType : "html",

    success : function(resul) {
      $("#capa_formularios").html(resul);
    },
    error : function(data) {
              var lb="";
              var errors = $.parseJSON(data.responseText);
               $.each(errors, function (key, value) {

                   $("#"+key+"_group").addClass( "has-error" );
                   $("#"+key+"_span").text(value);
               });

           $("#div_notificacion_rol").html('');
    }

  });
})

function asignar_rol(idusu){
   var idrol=$("#rol1").val();
   var urlraiz=$("#url_raiz_proyecto").val();
   $("#zona_etiquetas_roles").html($("#cargador_empresa").html());
   var miurl=urlraiz+"/asignar_rol/"+idusu+"/"+idrol+"";

    $.ajax({
    url: miurl
    }).done( function(resul)
    {
      var etiquetas="";
      var roles=$.parseJSON(resul);
      $.each(roles,function(index, value) {
        etiquetas+= '<span class="label label-warning">'+value+'</span> ';
      })

     $("#zona_etiquetas_roles").html(etiquetas);

    }).fail( function()
    {
    $("#zona_etiquetas_roles").html('<span style="color:red;">...Error: Aun no ha agregado roles o revise su conexion...</span>');
    }) ;

}

function quitar_rol(idusu){
   var idrol=$("#rol2").val();
   var urlraiz=$("#url_raiz_proyecto").val();
   $("#zona_etiquetas_roles").html($("#cargador_empresa").html());
   var miurl=urlraiz+"/quitar_rol/"+idusu+"/"+idrol+"";

    $.ajax({
    url: miurl
    }).done( function(resul)
    {
      var etiquetas="";
      var roles=$.parseJSON(resul);
      $.each(roles,function(index, value) {
        etiquetas+= '<span class="label label-warning" style="margin-left:10px;" >'+value+'</span> ';
      })

     $("#zona_etiquetas_roles").html(etiquetas);

    }).fail( function()
    {
    $("#zona_etiquetas_roles").html('<span style="color:red;">...Error: Aun no ha agregado roles  o revise su conexion...</span>');
    }) ;
}

function borrado_usuario(idusu){

   var urlraiz=$("#url_raiz_proyecto").val();
   $("#capa_modal").show();
   $("#capa_formularios").show();
   var screenTop = $(document).scrollTop();
   $("#capa_formularios").css('top', screenTop);
   $("#capa_formularios").html($("#cargador_empresa").html());
   var miurl=urlraiz+"/form_borrado_usuario/"+idusu+"";

    $.ajax({
    url: miurl
    }).done( function(resul)
    {
     $("#capa_formularios").html(resul);

    }).fail( function(resul)
   {
    $("#capa_formularios").html(resul);
   }) ;
}


function borrar_permiso(idrol,idper){

     var urlraiz=$("#url_raiz_proyecto").val();
     var miurl=urlraiz+"/quitar_permiso/"+idrol+"/"+idper+"";
     $("#filaP_"+idper+"").html($("#cargador_empresa").html() );
        $.ajax({
    url: miurl
    }).done( function(resul)
    {
     $("#filaP_"+idper+"").hide();

    }).fail( function()
   {
     alert("No se borro correctamente, intentalo nuevamente o revisa tu conexion");
   }) ;
}


function borrar_rol(idrol){

     var urlraiz=$("#url_raiz_proyecto").val();
     var miurl=urlraiz+"/borrar_rol/"+idrol+"";
     $("#filaR_"+idrol+"").html($("#cargador_empresa").html() );
        $.ajax({
    url: miurl
    }).done( function(resul)
    {
     $("#filaR_"+idrol+"").hide();

    }).fail( function()
   {
     alert("No se borro correctamente, intentalo nuevamente o revisa tu conexion");
   }) ;
}

//Funcion para cargar un archivo
$(document).on("submit",".formarchivo",function(e){

  e.preventDefault();
  var formu=$(this);
  var nombreform=$(this).attr("id");

  if(nombreform=="f_editar_evidencia_persona" ){ var miurl="editar_evidencia_persona";  var divresul="div_notificacion_sol"; }

  //información del formulario
  var formData = new FormData($("#"+nombreform+"")[0]);

  //hacemos la petición ajax
  $.ajax({
      url: miurl,
      type: 'POST',

      // Form data
      //datos del formulario
      data: formData,
      //necesario para subir archivos via ajax
      cache: false,
      contentType: false,
      processData: false,
      //mientras enviamos el archivo
      beforeSend: function(){
          $("#"+divresul+"").html($("#cargador_empresa").html());
      },
      //una vez finalizado correctamente
      success: function(data){
          $("#"+divresul+"").html(data);
          // $("#fotografia_usuario").attr('src', $("#fotografia_usuario").attr('src') + '?' + Math.random() );
      },
      //si ha ocurrido un error
      error: function(data){
          alert("ha ocurrido un error") ;

      }
  });
});
