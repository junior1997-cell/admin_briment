<!-- nombre de proyeto -->
<script>
  
  if (localStorage.getItem("nube_idproyecto") == 0 || localStorage.getItem("nube_idproyecto") == '0' || localStorage.getItem("nube_idproyecto") == null || localStorage.getItem("nube_idproyecto") === undefined ) {

    $("#ver-proyecto").html('<i class="fas fa-tools"></i> Selecciona un proyecto');
    $(".ver-otros-modulos-1").hide();

  } else {
    console.log("id proyecto actual: " + localStorage.getItem("nube_idproyecto"));
    $("#ver-proyecto").html(`<i class="fas fa-tools"></i> <p class="d-inline-block hide-max-width-1080px">Proyecto:</p> ${localStorage.getItem('nube_nombre_proyecto')}`);
    $(".ver-otros-modulos-1").show();
    //$('#icon_folder_'+localStorage.getItem('nube_idproyecto')).html('<i class="fas fa-folder-open"></i>');
  }
</script>

<script>

  $("#bloc_Accesos").on("click", function (e) {
    $("#bloc_Accesos").removeClass('bg-color-191f24').addClass('bg-color-191f24');
    $("#bloc_Recurso").removeClass('bg-color-191f24');
    $("#bloc_Compras").removeClass('bg-color-191f24');
    $("#bloc_Viaticos").removeClass('bg-color-191f24');
    $("#sub_bloc_comidas").removeClass('bg-color-191f24');    
    $("#bloc_PagosTrabajador").removeClass('bg-color-191f24');
  });

  $("#bloc_Recurso").on("click", function (e) {
    $("#bloc_Accesos").removeClass('bg-color-191f24');
    $("#bloc_Recurso").removeClass('bg-color-191f24').addClass('bg-color-191f24');
    $("#bloc_Compras").removeClass('bg-color-191f24');
    $("#bloc_Viaticos").removeClass('bg-color-191f24');
    $("#sub_bloc_comidas").removeClass('bg-color-191f24');    
    $("#bloc_PagosTrabajador").removeClass('bg-color-191f24');
  });  

  $("#bloc_Compras").on("click", function (e) {    
    $("#bloc_Accesos").removeClass('bg-color-191f24');
    $("#bloc_Recurso").removeClass('bg-color-191f24');
    $("#bloc_Compras").removeClass('bg-color-191f24').addClass('bg-color-191f24');
    $("#bloc_Viaticos").removeClass('bg-color-191f24');
    $("#sub_bloc_comidas").removeClass('bg-color-191f24');    
    $("#bloc_PagosTrabajador").removeClass('bg-color-191f24');
  });

  $("#bloc_Viaticos").on("click", function (e) {
    $("#bloc_Accesos").removeClass('bg-color-191f24');
    $("#bloc_Recurso").removeClass('bg-color-191f24');
    $("#bloc_Compras").removeClass('bg-color-191f24');
    $("#bloc_Viaticos").removeClass('bg-color-191f24').addClass('bg-color-191f24');
    $("#sub_bloc_comidas").removeClass('bg-color-191f24');    
    $("#bloc_PagosTrabajador").removeClass('bg-color-191f24');
  });  

  $("#sub_bloc_comidas").on("click", function (e) {
    $("#bloc_Accesos").removeClass('bg-color-191f24');
    $("#bloc_Recurso").removeClass('bg-color-191f24');
    $("#bloc_Compras").removeClass('bg-color-191f24');
    $("#bloc_Viaticos").removeClass('bg-color-191f24');
    $("#sub_bloc_comidas").removeClass('bg-color-191f24').addClass('bg-color-191f24');    
    $("#bloc_PagosTrabajador").removeClass('bg-color-191f24');
  });  

  $("#bloc_PagosTrabajador").on("click", function (e) {
    $("#bloc_Accesos").removeClass('bg-color-191f24');
    $("#bloc_Recurso").removeClass('bg-color-191f24');
    $("#bloc_Compras").removeClass('bg-color-191f24');
    $("#bloc_Viaticos").removeClass('bg-color-191f24');
    $("#sub_bloc_comidas").removeClass('bg-color-191f24');    
    $("#bloc_PagosTrabajador").removeClass('bg-color-191f24').addClass('bg-color-191f24');
  });

</script>