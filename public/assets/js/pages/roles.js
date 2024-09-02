document.addEventListener("DOMContentLoaded", function () {
    // Llamamos a la función para cargar los datos de los roles cuando se carga la página
    cargarDatosRoles();
});

// Esta función se encargará de cargar los datos en las tarjetas de roles
function cargarDatosRoles() {
    fetch(base_url + "roles/listar")
        .then(response => response.json())
        .then(data => {
            console.log('Datos de roles obtenidos:', data); // Mensaje en consola para verificar los datos obtenidos
            insertarDatosEnTarjetas(data);
        })
        .catch(error => console.error('Error al cargar roles:', error));
}

// Esta función se encargará de insertar los datos en las tarjetas
function insertarDatosEnTarjetas(roles) {
    const contenedorRoles = document.getElementById('contenedorRoles');
    contenedorRoles.innerHTML = ''; // Limpiamos el contenedor antes de insertar nuevos datos

    roles.forEach(function(rol) {
        if (!rol.nombre) {
            console.error('El rol no tiene un nombre definido:', rol);
            return;
        }

        // Crear la estructura de la tarjeta del rol
        const tarjeta = document.createElement('div');
        tarjeta.classList.add('col');
        tarjeta.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rol: ${rol.nombre}</h5>
                    <button onclick="editarRol(${rol.id})" class="btn btn-primary">Editar</button>
                    <button onclick="eliminarRol(${rol.id})" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        `;
        // Insertamos la tarjeta en el contenedor
        contenedorRoles.appendChild(tarjeta);
    });
}

// Función para eliminar un rol
function eliminarRol(id) {
    Swal.fire({
        title: '¿Está seguro de eliminar?',
        text: "¡El rol no se eliminará permanentemente, solo se cambiará el estado a inactivo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Sí!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "roles/eliminar/" + id;
            fetch(url)
                .then(response => response.json())
                .then(res => {
                    Swal.fire(
                        'Mensaje',
                        res.mensaje,
                        res.icono
                    );
                    cargarDatosRoles(); // Actualizar los datos de los roles después de eliminar un rol con éxito
                })
                .catch(error => console.error('Error al eliminar rol:', error));
        }
    });
}

// Función para redirigir a la página de edición de un rol
function editarRol(id) {
    window.location.href = base_url + "roles/editar/" + id;
}
