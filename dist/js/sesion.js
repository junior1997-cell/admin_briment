$(document).ready(function () {

  var hora_inicial_sesion = "00:30:00";
  var resta_hora = '';
  // se ejecuta cuando muvo lel maus
  $(document).mousemove(function (event) {  

    if (hora_inicial_sesion == "00:00:00" ) { location.reload(); }else{ hora_inicial_sesion = "00:30:00"; } 
      
  });


  // se ejecuta cada segundo
  setInterval(function(){    
    
    if ( resta_hora == "00:00:00") {
      hora_inicial_sesion = '00:00:00';
      $('#ver-sesion').html('Sesion expirada');  //console.log(hora_inicial_sesion);
    }else{
      resta_hora = restar_hora(hora_inicial_sesion, "00:00:01");
      hora_inicial_sesion = resta_hora;
      $('#ver-sesion').html(hora_inicial_sesion);  //console.log(hora_inicial_sesion);
    }    

  },1000); 
        

});

function restar_hora(h_i, h_f) {
  var hora1 = h_i.split(":"), hora2 = h_f.split(":"), t1 = new Date(), t2 = new Date();

  var h = "", m = "", s = "";

  t1.setHours(hora1[0], hora1[1], hora1[2]);
  t2.setHours(hora2[0], hora2[1], hora2[2]);

  //Aquí hago la resta
  t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());

  if (t1.getHours() < 10) { h = '0'+ t1.getHours(); }else{ h = t1.getHours(); }
  if (t1.getMinutes() < 10) { m = '0'+ t1.getMinutes(); }else{ m = t1.getMinutes(); }
  if (t1.getSeconds() < 10) { s = '0'+ t1.getSeconds(); }else{ s = t1.getSeconds(); }

  //Imprimo el resultado
  return h + ':' + m + ':' + s;         
}