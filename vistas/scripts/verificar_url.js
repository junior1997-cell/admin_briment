/**
 * Array de valores que contiene todas las urls a revisar.
 * Se puede poner el nombre del archivo y/o con ruta.
 *
 * NOTA: No funciona con archivos que se encuentren fuera del dominio donde se
 * ejecuta el script por temas de seguridad.
 */
 var urls = [
  {
    comprobante: "18164558987327.png",
    carpeta_file: "../dist/docs/compra/comprobante_compra/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/compra/comprobante_compra/18164558987327.png",
  },
  {
    comprobante: "0164671272121.pdf",
    carpeta_file: "../dist/docs/compra/comprobante_compra/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/compra/comprobante_compra/0164671272121.pdf",
  },
  {
    comprobante: "0164671272121.pdf",
    carpeta_file: "../dist/docs/compra/comprobante_compra/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/compra/comprobante_compra/0164671272121.pdf",
  },
  {
    comprobante: "18164558987327.png",
    carpeta_file: "../dist/docs/compra/comprobante_compra/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/compra/comprobante_compra/18164558987327.png",
  },
  {
    comprobante: "4164633504434.pdf",
    carpeta_file: "../dist/docs/otro_gasto/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/otro_gasto/comprobante/4164633504434.pdf",
  },
  {
    comprobante: "9164634009036.png",
    carpeta_file: "../dist/docs/otro_gasto/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/otro_gasto/comprobante/9164634009036.png",
  },
  {
    comprobante: "10164539374039.pdf",
    carpeta_file: "../dist/docs/transporte/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/transporte/comprobante/10164539374039.pdf",
  },
  {
    comprobante: "17164539372825.pdf",
    carpeta_file: "../dist/docs/transporte/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/transporte/comprobante/17164539372825.pdf",
  },
  {
    comprobante: "3164394709926.pdf",
    carpeta_file: "../dist/docs/pension/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/pension/comprobante/3164394709926.pdf",
  },
  {
    comprobante: "1164702712135.pdf",
    carpeta_file: "../dist/docs/break/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/break/comprobante/1164702712135.pdf",
  },
  {
    comprobante: "7164702707927.pdf",
    carpeta_file: "../dist/docs/break/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/break/comprobante/7164702707927.pdf",
  },
  {
    comprobante: "12164435530032.gif",
    carpeta_file: "../dist/docs/break/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/break/comprobante/12164435530032.gif",
  },
  {
    comprobante: "3164539418737.pdf",
    carpeta_file: "../dist/docs/comida_extra/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/comida_extra/comprobante/3164539418737.pdf",
  },
  {
    comprobante: "7164539415940.pdf",
    carpeta_file: "../dist/docs/comida_extra/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/comida_extra/comprobante/7164539415940.pdf",
  },
  {
    comprobante: "1164394686425.pdf",
    carpeta_file: "../dist/docs/comida_extra/comprobante/",
    host: "http://localhost/",
    ruta: "http://localhost/admin_briment/dist/docs/comida_extra/comprobante/1164394686425.pdf",
  },
];

function verificarUrl(url) {
  // variable que contiene la url a revisar
  var url = url;

  // variable que contiene los milisegundos de cuando inicia
  var d = Date.now();

  /* Función que se ejecuta cada vez que cambia el estado */
  readystatechangeListener = function (url, e) {
    // document.querySelectorAll("[data-url='" + url + "']")[0].getElementsByClassName("status")[0].innerHTML = e.status;
    console.log("readystatechange - url: " + e.responseURL + " - status: " + e.status);
  };

  /* Función que se ejecuta cada vez que cambia el size del progreso del archivo  */
  progressListener = function (url) {
    oEvent = window.event;
    if (oEvent.lengthComputable) {
      var percentComplete = (oEvent.loaded / oEvent.total) * 100;
      console.log("progress:  - url: " + oEvent.srcElement.responseURL + " - " + percentComplete);
    } else {
      console.log("loaded:  - url: " + oEvent.srcElement.responseURL + " - " + oEvent.loaded);
    }

    var size = oEvent.loaded;
    if (size > 1024) {
      size = (size / 1024).toFixed(2) + " Kb";
    } else {
      size = size.toFixed(2) + " B";
    }
    // document.querySelectorAll("[data-url='" + url + "']")[0].getElementsByClassName("size")[0].innerHTML = numberFormat(size);
  };

  /* Función que se ejecuta cuando se carga el contenido del archivo  */
  loadListener = function () {
    console.log("load - url: " + this.responseURL + " - status: " + this.status);
    if (this.status == 200) {
      //console.log("load: \n-------------------------------\n"+this.responseText+"\n-------------------------------\n");
    }
  };

  /* Función que se ejecuta cuando finaliza la carga del archivo */
  loadendListener = function () {
    console.log("finish loader - url: " + this.responseURL + " - status: " + this.status);
    // document.querySelectorAll("[data-url='" + url + "']")[0].getElementsByClassName("time")[0].innerHTML = Date.now() - d;
  };

  /* Función que se ejecuta si ha habiado algun error  */
  errorListener = function () {
    console.log("error - url: " + this.responseURL + " - " + this.statusText);
  };

  /* Funcion que se ejecuta si el proceso ha sido interrumpido por el usuario */
  abortListener = function () {
    console.log("La transferencia se ha cancelado - url: " + this.responseURL);
  };

  /* función inicial que genera los eventos y realiza la peticion del archivo */
  this.start = function () {
    var oReq = new XMLHttpRequest();
    oReq.addEventListener("readystatechange", function () {
      readystatechangeListener(url, this);
    });
    oReq.addEventListener("progress", function () {
      progressListener(url, this);
    });
    oReq.addEventListener("load", loadListener);
    oReq.addEventListener("loadend", loadendListener);
    oReq.addEventListener("error", errorListener);
    oReq.addEventListener("abort", abortListener);
    oReq.open("GET", url, true);
    oReq.send();
  };
}

var elementos = [];

/* Bucle que va recorriendo cada una de las urls y va generando un objeto para cada una de ellas */
urls.forEach((element, key) => {
  // createElementUrl(element.ruta);
  elementos[key] = new verificarUrl(element.ruta);
  // console.log(elementos[key]);
  elementos[key].start();
});

// for (var i = 0; i < urls.length; i++) {
//   createElementUrl(urls[i]);
//   elementos[i] = new verificarUrl(urls[i]);
//   elementos[i].start();
// }

/* Funcion que genera los divs necesarios para mostrar una url en la pagina */
function createElementUrl(url) {
  var div = document.createElement("div");
  div.dataset.url = url;

  var divText = document.createElement("div");
  divText.className = "text";
  divText.innerHTML = url;

  var divStatus = document.createElement("div");
  divStatus.className = "status";

  var divTime = document.createElement("div");
  divTime.className = "time";
  divTime.innerHTML = "-";

  var divSize = document.createElement("div");
  divSize.className = "size";

  div.appendChild(divText);
  div.appendChild(divStatus);
  div.appendChild(divTime);
  div.appendChild(divSize);

  // document.getElementById("info").appendChild(div);
}

/**
 * Funcion que devuelve un numero separando los separadores de miles
 * Puede recibir valores negativos y con decimales
 */
function numberFormat(numero) {
  // Variable que contendra el resultado final
  var resultado = "";

  // Si el numero empieza por el valor "-" (numero negativo)
  if (numero[0] == "-") {
    // Cogemos el numero eliminando los posibles puntos que tenga, y sin
    // el signo negativo
    nuevoNumero = numero.replace(/\,/g, "").substring(1);
  } else {
    // Cogemos el numero eliminando los posibles puntos que tenga
    nuevoNumero = numero.replace(/\,/g, "");
  }

  // Si tiene decimales, se los quitamos al numero
  if (numero.indexOf(".") >= 0) nuevoNumero = nuevoNumero.substring(0, nuevoNumero.indexOf("."));

  // Ponemos un punto cada 3 caracteres
  for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++) resultado = nuevoNumero.charAt(i) + (j > 0 && j % 3 == 0 ? "," : "") + resultado;

  // Si tiene decimales, se lo añadimos al numero una vez forateado con
  // los separadores de miles
  if (numero.indexOf(".") >= 0) resultado += numero.substring(numero.indexOf("."));

  if (numero[0] == "-") {
    // Devolvemos el valor añadiendo al inicio el signo negativo
    return "-" + resultado;
  } else {
    return resultado;
  }
}
