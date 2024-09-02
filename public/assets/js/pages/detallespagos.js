$(document).ready(function() {
   if (window.location.pathname.includes('/public/pagos')) {
        
        // Definir la función para editar un pago en el ámbito global
        window.editarPago = function(id, monto, pmora1, num) {
            pmora1 = pmora1 || '0'; // Asignar 0 si pmora1 no tiene un valor
        
            Swal.fire({
                title: 'Editar Pago',
                html:
                    '<label for="monto">Monto:</label>' +
                    '<input id="swal-input1" class="swal2-input" value="' + monto + '" type="number" step="0.01">' +
                    '<label for="pmora1">pmora1:</label>' +
                    '<input id="swal-input2" class="swal2-input" value="' + pmora1 + '" type="text">' +
                    '<label for="num">Número:</label>' +
                    '<input id="swal-input3" class="swal2-input" value="' + num + '" type="text">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const nuevoMonto = document.getElementById('swal-input1').value;
                    const nuevoPmora1 = document.getElementById('swal-input2').value;
                    const nuevoNum = document.getElementById('swal-input3').value;
        
                    if (!nuevoMonto || isNaN(nuevoMonto)) {
                        Swal.showValidationMessage('Por favor, ingresa un monto válido');
                        return;
                    }

                    return Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¿Quieres guardar los cambios?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return $.ajax({
                                url: '/public/pagos/editarPago', // Asegúrate de que esta URL sea correcta
                                method: 'POST',
                                data: {
                                    id: id,
                                    monto: nuevoMonto,
                                    pmora1: nuevoPmora1,
                                    num: nuevoNum
                                },
                                dataType: 'json'
                            }).then(response => {
                                if (response.success) {
                                    return response;
                                } else {
                                    Swal.showValidationMessage(`Error: ${response.message}`);
                                }
                            }).catch(error => {
                                Swal.showValidationMessage(`Error: ${error}`);
                            });
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Datos actualizados',
                        text: 'El pago ha sido actualizado exitosamente.'
                    }).then(() => {
                        cargarDatos(); // Recargar los datos para reflejar los cambios
                    });
                }
            });
        }

        // Función para cargar los datos de los pagos
        function cargarDatos() {
            $.ajax({
                url: '/public/pagos/listar', // URL para listar los pagos
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var tarjetas = '';
                    $.each(data, function(index, pago) {
                        // Asegurarse de que el nombre completo del cliente se muestra correctamente
                        var nombreCliente = pago.nombre_completo || 'N/A';

                        tarjetas += '<div class="col-lg-3 col-md-4 col-sm-6 mb-3">';
                        tarjetas += '    <div class="card">';
                        tarjetas += '        <div class="card-body">';
                        tarjetas += '            <h5 class="card-title">ID: ' + pago.id + '</h5>';
                        tarjetas += '            <p class="card-text">Monto: ' + pago.monto + '</p>';
                        tarjetas += '            <p class="card-text">Fecha: ' + pago.fecha + '</p>';
                        tarjetas += '            <p class="card-text">pmora1: ' + (pago.pmora1 || '0') + '</p>';
                        tarjetas += '            <p class="card-text">Número: ' + pago.num + '</p>';
                        tarjetas += '            <hr>';
                        tarjetas += '            <h6 class="card-subtitle mb-2 text-muted">Detalles del Préstamo</h6>';
                        tarjetas += '            <p class="card-text">Cuota: ' + (pago.cuota || 'N/A') + '</p>';
                        tarjetas += '            <p class="card-text">Abono: ' + (pago.abono || 'N/A') + '</p>';
                        tarjetas += '            <p class="card-text">Importe Cuota: ' + (pago.importe_cuota || 'N/A') + '</p>';
                        tarjetas += '            <hr>';
                        tarjetas += '            <h6 class="card-subtitle mb-2 text-muted">Detalles del Cliente</h6>';
                        tarjetas += '            <p class="card-text">Nombre: ' + nombreCliente + '</p>';
                        tarjetas += '            <a href="#" class="btn btn-primary mr-2" onclick="editarPago(' + pago.id + ', ' + pago.monto + ', \'' + (pago.pmora1 || '0') + '\', \'' + pago.num + '\')">';
                        tarjetas += '                <i class="fas fa-edit"></i>';
                        tarjetas += '            </a>';
                        tarjetas += '        </div>';
                        tarjetas += '    </div>';
                        tarjetas += '</div>';
                    });
                    $('#tarjetasPagos').html(tarjetas);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los datos.',
                    });
                }
            });
        }

        cargarDatos(); // Cargar los datos al iniciar la página

    }
});
