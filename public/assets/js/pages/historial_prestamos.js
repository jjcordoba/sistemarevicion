const btnCorreo = document.querySelector("#btnCorreo");
const btnWhatsapp = document.querySelector("#btnWhatsapp");
const inputCorreo = document.querySelector("#inputCorreo");
const inputTelefono = document.querySelector("#inputTelefono");
const btnAccion = document.querySelector("#btnAccion");
const mensaje = document.querySelector("#mensaje");
const id_cuota = document.querySelector("#id_cuota");
const monto = document.querySelector("#monto");
const metodo = document.querySelector("#metodo");
const operacion = document.querySelector("#operacion");
const pmora = document.querySelector("#pmora");
const btnAbono = document.querySelector("#btnAbono");
const title = document.querySelector("#title");

const id_prestamo = document.querySelector("#id_prestamo");
const monto_cancelar = document.querySelector("#monto_cancelar");
const metodo_cancelar = document.querySelector("#metodo_cancelar");
const operacion_cancelar = document.querySelector("#operacion_cancelar");
const btnAbono_cancelar = document.querySelector("#btnAbono_cancelar");

const myModal = new bootstrap.Modal(document.querySelector("#modalMensaje"));
const modalAbono = new bootstrap.Modal(document.querySelector("#modalAbono"));
const modalCancelar = new bootstrap.Modal(document.querySelector("#modalCancelar"));
let accion = 1;
document.addEventListener("DOMContentLoaded", function () {
  // Funcionalidad para el botón de correo electrónico
  btnCorreo.addEventListener("click", function () {
    if (inputCorreo.value === "" || mensaje.value === "") {
      Swal.fire("Mensaje", "Ingresa un mensaje", "warning");
    } else {
      enviarCorreo(inputCorreo.value, mensaje.value);
    }
  });


  btnAbono.addEventListener("click", function () {
    //alert('btnAbono');
    if (id_cuota.value === "" || monto.value === "" || metodo.value === "") {
      Swal.fire("Mensaje", "TODOS LOS CAMPOS CON * SON REQUERIDOS", "warning");
    } else {
      Swal.fire({
        title: "¿Está seguro de agregar abono?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "¡Sí!",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = base_url + "prestamos/agregarAbono";
          let data = new FormData();
          data.append("id", id_cuota.value);
          data.append("monto", monto.value);
          data.append("metodo", metodo.value);
          data.append("operacion", operacion.value);
          data.append("pmora", pmora.value);
          const http = new XMLHttpRequest();
          http.open("POST", url, true);
          http.send(data);
          http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
              const res = JSON.parse(this.responseText);
              console.log(res);
              Swal.fire("Mensaje", res.mensaje, res.icono);
              if (res.icono == "success") {
                setTimeout(() => {
                  Swal.fire({
                    title: '¿Imprimir comprobante?',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, generar'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.open(base_url + 'prestamos/' + res.idPago + '/ticketPago', '_blanl');
                    }
                    window.location.reload();
                  })
                }, 1500);
              }
              // Llamada a la función para actualizar el estado de las cuotas
              
              //actualizarEstadoCuotas();
            }
          };
        }
      });
    }
  });

// Función para actualizar el estado de las cuotas
function actualizarEstadoCuotas() {
    const url = base_url + "prestamos/actualizarEstadoCuotas";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            console.log(res); // Puedes hacer lo que necesites con la respuesta del servidor
        }
    };
}


  btnAbono_cancelar.addEventListener("click", function () {
    if (id_prestamo.value == "" || monto_cancelar.value == "" || metodo_cancelar.value == "") {
      Swal.fire("Mensaje", "TODO LOS CAMPOS CON * SON REQUERIDOS", "warning");
    } else {
      Swal.fire({
        title: "Esta seguro de cancelar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si!",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = base_url + "prestamos/cancelarAbono";
          let data = new FormData();
          data.append("id", id_prestamo.value);
          data.append("monto", monto_cancelar.value);
          data.append("metodo", metodo_cancelar.value);
          data.append("operacion", operacion_cancelar.value);
          // data.append("pmora", pmora.value);
          const http = new XMLHttpRequest();
          http.open("POST", url, true);
          http.send(data);
          http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
              const res = JSON.parse(this.responseText);
              Swal.fire("Mensaje", res.mensaje, res.icono);              
              if (res.icono == "success") {
                setTimeout(() => {
                  Swal.fire({
                    title: 'Imprimir comprobante?',
                    showCancelButton: true,
                    confirmButtonText: 'Si generar'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.open(base_url + 'prestamos/' + res.idPago + '/ticketPago', '_blanl');
                    }
                    window.location.reload();
                  })
                }, 1500);
              }
            }
          };
        }
      });
    }
  });
});

function enviarCorreo(correo, mensaje) {
  const url = base_url + "prestamos/enviarCorreo";
  let data = new FormData();
  data.append("correo", correo);
  data.append("mensaje", mensaje);
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(data);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res.tipo == "success") {
        mensaje.value = "";
        myModal.hide();
      }
      Swal.fire("Mensaje", res.msg, res.tipo);
    }
  };
}

function cambiarEstado(id) {
  const url = base_url + "prestamos/restante/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      id_cuota.value = id;
      title.textContent = "PAGO COMPLETO";
      operacion.value = '';
      // pmora.removeAttribute('readonly');
      monto.value = parseFloat(res.importe_cuota) - parseFloat(res.abono);
      monto.setAttribute('readonly', true);
      modalAbono.show();
    }
  };
}

function agregarAbono(id) {
  id_cuota.value = id;
  title.textContent = "ABONO";
  monto.value = '';
  operacion.value = '';
  // pmora.value = '';
  // pmora.removeAttribute('readonly');
  monto.removeAttribute('readonly');
  modalAbono.show();
}

function cancelarAbono(idPrestamo) {
  const url = base_url + "prestamos/getAbonos/" + idPrestamo;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      id_prestamo.value = idPrestamo;
      operacion_cancelar.value = '';
      let total = parseFloat(res.prestamo.importe_cuota) - parseFloat(res.abonos);
      monto_cancelar.value = total.toFixed(2);
      monto_cancelar.setAttribute('readonly', true);
      modalCancelar.show();
    }
  };
}
