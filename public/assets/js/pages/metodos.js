document.addEventListener("DOMContentLoaded", function () {
    // Cargar métodos mediante AJAX
    fetch(base_url + 'metodos/listar')
        .then(response => response.json())
        .then(data => {
            const metodosContainer = document.getElementById('metodosContainer');
            metodosContainer.innerHTML = ''; // Limpiar el contenido antes de agregar nuevos métodos
            data.forEach(metodo => {
                const card = `
                    <div class="col mb-4">
                        <div class="card border border-info">
                            <div class="card-body">
                                <h5 class="card-title">${metodo.nombre}</h5>
                                <p class="card-text">${metodo.estado}</p>
                                <a href="${base_url}metodos/editar/${metodo.id}" class="btn btn-primary">Editar</a>
                                <button type="button" class="btn btn-danger" onclick="eliminarMetodo(${metodo.id})">Eliminar</button>
                                </div>
                        </div>
                    </div>
                `;
                metodosContainer.innerHTML += card;
            });
        })
        .catch(error => console.error('Error al cargar métodos:', error));
});

function eliminarMetodo(id) {
    Swal.fire({
        title: '¿Estás seguro de eliminar?',
        text: "El método no se eliminará de forma permanente, solo cambiará el estado a inactivo.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = `${base_url}metodos/eliminar/${id}`;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    Swal.fire(
                        res.mensaje,
                        '',
                        res.icono
                    ).then(() => {
                        if (res.icono === 'success') {
                            window.location.reload(); // Redireccionar después de aceptar la eliminación
                        }
                    });
                }
            }
        }
    });

    // Evitar que la página se recargue automáticamente
    event.preventDefault();
}
