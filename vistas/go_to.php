<div id="js-go-to"> 
  <a class="js-go-to go-to position-fixed animated hs-go-to-prevent-event fadeInUp" href="#" style="right: 15px;bottom: 15px;">
    <i class="fas fa-angle-up"></i>
  </a>
</div>

<script>
  $(function () {
    $(document).on("scroll", function () {
      var desplazamientoActual = $(document).scrollTop();
      var controlArriba = $("#js-go-to");
      // console.log("Estoy en ", desplazamientoActual);
      if (desplazamientoActual > 100 && controlArriba.css("display") == "none") { controlArriba.fadeIn(500); }
      if (desplazamientoActual < 100 && controlArriba.css("display") == "block") { controlArriba.fadeOut(500); }
    });

    $("#js-go-to a").on("click", function (e) {
      e.preventDefault();
      console.log('vamos ariba');
      $("html, body").animate( { scrollTop: 0,  }, 600 ); 
    });   
  });
</script>