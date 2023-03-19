
// localStorage.removeItem("nube_idproyecto");
// localStorage.removeItem("nube_fecha_inicial_proyecto");
// localStorage.removeItem("nube_fecha_final_proyecto");
// localStorage.removeItem("nube_nombre_proyecto");

localStorage.setItem('nube_idproyecto', 0);
localStorage.setItem('nube_fecha_inicial_proyecto', '');
localStorage.setItem('nube_fecha_final_proyecto', '');
localStorage.setItem('nube_nombre_proyecto', '');

$("#frmAcceso").on('submit',function(e) {
    $('.login-btn').html('<i class="fas fa-spinner fa-pulse fa-lg"></i> Comprobando...').removeClass('btn-outline-warning').addClass('btn-info disabled');
	e.preventDefault();
    
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    $.post("../ajax/usuario.php?op=verificar",{"logina":logina,"clavea":clavea}, function(e){
        try {
            e = JSON.parse(e); //console.log(e);

            setTimeout(validar_response(e), 1000);
            
        } catch (error) {
            $('.login-btn').html('Ingresar').removeClass('disabled btn-info').addClass('btn-outline-warning');
            ver_errores(error);             
        }
    });
})

function validar_response(e) {
    if (e.status == true){
        if (e.data == null ) {
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Usuario y/o Password incorrectos',
                subtitle: 'cerrar',
                body: 'Ingrese sus credenciales correctamente, o pida al administrador de sistema restablecer sus credenciales.'
            });
            $('.login-btn').html('Ingresar').removeClass('disabled btn-info').addClass('btn-outline-warning');
        } else {
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Bienvenido al sistema "Admin Briment"',
                subtitle: 'cerrar',
                body: 'Se inicio sesion correctamente. Te hemos extrañado, estamos muy contentos de tenerte de vuelta.'
            });
            var redirecinando = varaibles_get();

            if (redirecinando.file == '' || redirecinando.file == null ) {
                //console.log('vacio perrro');
                $(location).attr("href","escritorio.php");
            } else {
                //console.log(redirecinando.file);
                //$(location).attr("href",redirecinando.file);  
                $(location).attr("href","escritorio.php");                              
            }
            //console.log(redirecinando);            
        }
        
    } else {
        $('.login-btn').html('Ingresar').removeClass('disabled btn-info').addClass('btn-outline-warning');
        ver_errores(e); 
    }
}

function varaibles_get() {
    var v_args = location.search.substring(1).split("&");
    var param_values = [];
    if ( v_args != '' && v_args != 'undefined')
    for (var i = 0; i < v_args.length; i++) {
        var pair = v_args[i].split("=");
        if ( typeOfVar( pair ) === 'array' ) {
            param_values[ decodeURIComponent( pair[0] ) ] = decodeURIComponent( pair[1] );
        }
    }
    return param_values;
}

function typeOfVar (obj) {
    return {}.toString.call(obj).split(' ')[1].slice(0, -1).toLowerCase();
}

// carrucell

var cont =1;
let conteo = setInterval(()=> {    
    $('.login-page').css({'background-image':`linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../dist/img/fondo-${cont}.jpg')`});
    cont++;
    if (cont == 5){ cont =0; }
}, 5000);

function ver_password() {
    var x = document.getElementById("clavea");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
}