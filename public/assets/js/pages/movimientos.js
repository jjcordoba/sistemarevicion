document.addEventListener("DOMContentLoaded", function () {
    // Llamamos a la función para cargar los datos de las tarjetas cuando se carga la página
    cargarDatosTarjetas();
});

// Esta función se encargará de cargar los datos en las tarjetas
function cargarDatosTarjetas() {
    $.ajax({
        url: base_url + "movimientos/listar",
        method: "GET",
        dataType: "json",
        success: function (data) {
            insertarDatosEnTarjetas(data);
        }
    });
}

// Esta función se encargará de insertar los datos en las tarjetas
function insertarDatosEnTarjetas(movimientos) {
    const contenedorTarjetas = document.getElementById('contenedorTarjetas');
    contenedorTarjetas.innerHTML = ''; // Limpiamos el contenedor antes de insertar nuevos datos

    movimientos.forEach(function(movimiento) {
        // Creamos la estructura de la tarjeta
        const tarjeta = document.createElement('div');
        tarjeta.classList.add('col');
        tarjeta.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes: ${movimiento.mes}</h5>
                    <p class="card-text">Recaudado: ${movimiento.monto_rec}</p>
                    <p class="card-text">Cuenta Bancaria: ${movimiento.cuenta_banc}</p>
                    <p class="card-text">Efectivo: ${movimiento.efectivo}</p>
                    <p class="card-text">Otros: ${movimiento.otros}</p>
                    <button onclick="eliminarMov(${movimiento.id})" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        `;
        // Insertamos la tarjeta en el contenedor
        contenedorTarjetas.appendChild(tarjeta);
    });
}

function eliminarMov(id) {
    // Función para eliminar un movimiento
    Swal.fire({
        title: '¿Está seguro de eliminar?',
        text: "¡El movimiento no se eliminará permanentemente, solo se cambiará el estado a inactivo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Sí!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "movimientos/eliminar/" + id;
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
                    cargarDatosTarjetas(); // Actualizamos los datos de las tarjetas después de eliminar un movimiento
                }
            }
        }
    })
}