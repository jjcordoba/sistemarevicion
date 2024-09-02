document.addEventListener("DOMContentLoaded", function () {
    $.ajax({
        url: base_url + "clientes/listar",
        dataType: 'json',
        success: function (data) {
            var cardContainer = $('#cardContainer');
            cardContainer.empty(); // Limpiamos el contenedor antes de agregar las tarjetas

            // Iteramos sobre los datos y creamos una tarjeta por cada cliente
            data.forEach(function (cliente) {
                var card = $('<div class="card mx-2 my-2" style="width: 18rem;">' +
                                '<div class="row g-0">' +
                                    '<div class="col-md-12">' +
                                        '<div class="card-body">' +'<div class=" imgperfil col-md-12">' +
                                        '<img src="' + base_url + cliente.imgperfil + '" class="img-fluid rounded-end" alt="Imagen de perfil">' +
                                    '</div>' +'<div class=" nombre">' +
                                            '<h5 class="card-title">' + cliente.nombre + ' ' + cliente.apellido + '</h5>' +'</div>'+
                                            '<div class="col-md-12">' +
                                            '<h6 class="card-subtitle mb-2 text-muted anho100">Documento N° ' + cliente.num_identidad + '</h6>' +
                                            '<p class="card-text anho100">Teléfono: ' + cliente.telefono + '</p>' +
                                            '<p class="card-text anho100">Dirección: ' + cliente.direccion + '</p>' +
                                            '<p class="card-text anho100">Referencia: ' + cliente.referencia + '</p>' +
                                            '<p class="card-text anho100">Estado: ' + cliente.estado + '</p>' +
                                            '<button class="btn btn-secondary" onclick="agregarImagenes(' + cliente.id + ')"><i class="fas fa-camera"></i></button>' +
                                            '<a href="' + base_url + 'clientes/editar/' + cliente.id + '" class="btn btn-info"><i class="fas fa-edit"></i></a>' +
                                            '<button class="btn btn-danger" onclick="eliminarCli(' + cliente.id + ')"><i class="fas fa-trash-alt"></i></button>' +
                                        '</div>' +
                                    '</div>' +
                                    
                                '</div>' +
                            '</div>');
                cardContainer.append(card); // Agregamos la tarjeta al contenedor
            });
        }
    });
});
function eliminarCli(id) {
    Swal.fire({
        title: 'Esta seguro de eliminar?',
        text: "El cliente no se eliminará de forma permanente, solo cambiará el estado a inactivo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "clientes/eliminar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    Swal.fire(
                        'Mensaje',
                        res.mensaje,
                        res.icono
                    )
                    t_cli.ajax.reload();
                }
            }

        }
    })
}

//########### IMAGENES Y DOCUMENTOS ##########
let myDropzone = new Dropzone("#frmImagenes", {
  dictDefaultMessage: "Selecciona o arrastra el documento y/o imagen para subir",
  acceptedFiles: ".png, .jpg, .jpeg, .pdf",
  maxFiles: 10,
  addRemoveLinks: true,
  autoProcessQueue: false,
  parallelUploads: 10,
});

btnProcesar.addEventListener("click", function () {
  myDropzone.processQueue();
});

myDropzone.on("complete", function (file) {
  myDropzone.removeFile(file);
  Swal.fire("Mensaje", "ARCHIVO SUBIDO", "success");
  setTimeout(() => {
      modalGaleria.hide();
  }, 1500);
});

function agregarImagenes(id_cliente) {
  const url = base_url + "clientes/docs/" + id_cliente;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          document.querySelector("#id_cliente").value = id_cliente;
          let html = "";
          let destino = base_url + "assets/clientes/" + id_cliente + "/";
          for (let i = 0; i < res.length; i++) {
              let extension = res[i].split('.').pop(); // Obtener la extensión del archivo
              if (extension.toLowerCase() === 'pdf') { // Si es un archivo PDF
                  html += `<div class="col-md-3 text-center">
                              <a href="${destino + res[i]}" target="_blank" rel="noopener noreferrer">
                                  <i class="fa-solid fa-file-pdf"></i>
                              </a>
                              <div class="d-grid">
                                  <button class="btn btn-danger btnEliminarImagen" type="button" data-id="${id_cliente}" data-name="${id_cliente}/${res[i]}"><i class="fas fa-trash"></i></button>
                                 <!-- <a class="btn btn-primary" href="${destino + res[i]}" target="_blank" rel="noopener noreferrer">Ver PDF</a>-->
                                  <a class="btn btn-success" href="${destino + res[i]}" download="${res[i]}" target="_blank">Descargar PDF</a>
                              </div>     
                          </div>`;
              } else { // Si es una imagen
                  html += `<div class="col-md-3 text-center">
                              <a href="${destino + res[i]}" target="_blank">
                                  <img src="${destino + res[i]}" alt="Imagen" width="100" height="100">
                              </a>
                              <div class="d-grid">
                                  <button class="btn btn-danger btnEliminarImagen" type="button" data-id="${id_cliente}" data-name="${id_cliente}/${res[i]}"><i class="fas fa-trash"></i></button>
                                  <a class="btn btn-primary" href="${destino + res[i]}" target="_blank">Ver Imagen</a>
                                  <a class="btn btn-success" href="${destino + res[i]}" download="${res[i]}" target="_blank">Descargar Imagen</a>
                              </div>     
                          </div>`;
              }
          }
          containerGaleria.innerHTML = html;
          eliminarImagen();
          modalGaleria.show();
      }
  };
}




function eliminarImagen() {
  let lista = document.querySelectorAll(".btnEliminarImagen");
  for (let i = 0; i < lista.length; i++) {
    lista[i].addEventListener("click", function () {
      let id_cliente = lista[i].getAttribute("data-id");
      let nombre = lista[i].getAttribute("data-name");
      eliminar(id_cliente, nombre);
    });
  }
}

function eliminar(id_cliente, nombre) {
  const url = base_url + "clientes/eliminarImg";
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(
    JSON.stringify({
      url: nombre,
    })
  );
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      Swal.fire("Mensaje", res.msg, res.icono);
      if (res.icono == "success") {
        agregarImagenes(id_cliente);
      }
    }
  };
}