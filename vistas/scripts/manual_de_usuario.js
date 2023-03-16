var tabla;

//Función que se ejecuta al inicio
function init() {

  $("#mManualDeUsuario").addClass("active");

  // Formato para telefono
  $("[data-mask]").inputmask();
}


init();

// .....::::::::::::::::::::::::::::::::::::: V A L I D A T E   F O R M  :::::::::::::::::::::::::::::::::::::::..

$(function () {   

  $('#unidad_medida').on('change', function() { $(this).trigger('blur'); });
  $('#color').on('change', function() { $(this).trigger('blur'); });

  $("#form-materiales").validate({
    rules: {
      nombre_material:      { required: true },
      descripcion_material: { minlength: 4 },
      unidad_medida:          { required: true },
      color:                { required: true },
      precio_unitario:      { required: true },
    },
    messages: {
      nombre_material:      { required: "Campo requerido.", },
      descripcion_material: { minlength: "MINIMO 4 caracteres." },
      unidad_medida:          { required: "Campo requerido.", },
      color:                { required: "Campo requerido.", },
      precio_unitario:      { required: "Campo requerido.", },
    },

    errorElement: "span",

    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },

    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },

    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid").addClass("is-valid");
    },
    
    submitHandler: function (e) {
      $(".modal-body").animate({ scrollTop: $(document).height() }, 600); // Scrollea hasta abajo de la página
      guardaryeditar(e);
    },
  });

  $('#unidad_medida').rules('add', { required: true, messages: {  required: "Campo requerido" } });
  $('#color').rules('add', { required: true, messages: {  required: "Campo requerido" } });
});

// .....::::::::::::::::::::::::::::::::::::: F U N C I O N E S    A L T E R N A S  :::::::::::::::::::::::::::::::::::::::..

