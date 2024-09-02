let t_prestamos;
const id_cliente = document.getElementById('id_cliente');
const tasa_interes = document.getElementById('tasa_interes');
const importe_credito = document.getElementById('importe_credito');
const total_pagar = document.getElementById('total_pagar');
const cuotas = document.getElementById('cuotas');
const importe_cuota = document.getElementById('importe_cuota');
const interes_generado = document.getElementById('interes_generado');
const errorBusqueda = document.querySelector('#errorBusqueda');
const desde = document.querySelector('#desde');
const hasta = document.querySelector('#hasta');


document.addEventListener('DOMContentLoaded', function () {
    // Prestamos
    listarPrestamos('hoy');
});


document.addEventListener('DOMContentLoaded', function () {
    $("#buscarCliente").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: base_url + 'prestamos/buscarCliente',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function (data) {
                    response(data);
                    if (data.length > 0) {
                        errorBusqueda.textContent = '';
                    } else {
                        errorBusqueda.textContent = 'NO HAY CLIENTE CON ESE NOMBRE';
                    }
                }
            });
        },
        select: function (event, ui) {
            id_cliente.value = ui.item.id;
        }
    })

    //importe_credito
    importe_credito.addEventListener('keyup', function (e) {
        if (e.target.value != '') {
            //validacion de interes
            let interes = (tasa_interes.value == '' 
            || tasa_interes.value < 1) ? 0 : tasa_interes.value;
            //validacion de coutas
            let cuotas_total = (cuotas.value == '' 
            || cuotas.value < 0) ? 0 : cuotas.value;
            calcularTotales(e.target.value, cuotas_total, interes);
        } else {
            resetTotales();
        }
    });

    //cuotas
    cuotas.addEventListener('change', function (e) {
        if (e.target.value != '') {
            //validacion de interes
            let interes = (tasa_interes.value == '' 
            || tasa_interes.value < 1) ? 0 : tasa_interes.value;
            //validacion de importe
            let importe = (importe_credito.value == '' 
            || importe_credito.value < 0) ? 0 : importe_credito.value;
            calcularTotales(importe, e.target.value, interes);
        } else {
            resetTotales();
        }
    });

    // cambiar tasa interes
    tasa_interes.addEventListener('keyup', function (e) {
        if (e.target.value != '') {
            //validacion de cuotas
            let cuotas_tasa = (cuotas.value == '' 
            || cuotas.value < 1) ? 0 : cuotas.value;
            //validacion de importe
            let importe = (importe_credito.value == '' 
            || importe_credito.value < 0) ? 0 : importe_credito.value;
            calcularTotales(importe, cuotas_tasa, e.target.value);
        } else {
            resetTotales();
        }
    });

    //prestamos
    t_prestamos = $('#tblPrestamos').DataTable({
        "aPreocesing": true,
        "aServerSide": true,
        "ajax": {
            "url": "" + base_url + "prestamos/listar",
            "dataSrc": ""
        },
        "columns": [
        {
            "data": "cliente"
        },
        {
            "data": "importe"
        }
        ,
        {
            "data": "vencimiento"
        },
        {
            "data": "interes"
        },
        {
            "data": "acciones"
        }
        ],
        "resonsieve": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [
            [0, "desc"]
        ],
        "dom": "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons,
        language,
        "createdRow": function (row, data, index) {
            //pintar una celda
            if (data.f_venc < data.fecha_actual && data.estado == 1) {
                //pintar una fila
                $('td', row).css({
                    'background-color': '#db704c',
                    'color': 'white'
                });
            } else if (data.f_venc == data.fecha_actual && data.estado == 1) {
                $('td', row).css({
                    'background-color': '#ffdb4c',
                    'color': 'white'
                });
            }
        },
    });
})

function calcularTotales(importe, cuotas, interes) {
    //interes generado
    let ganancia = parseFloat(importe) * (parseInt(interes) / 100);
    let importeCuota = 0;
    if (cuotas > 0) {
        importeCuota = (parseFloat(importe) / parseInt(cuotas)) + (parseFloat(ganancia) / parseInt(cuotas));
    }
    importe_cuota.value = importeCuota.toFixed(2);
    //interes generado
    interes_generado.value = ganancia.toFixed(2);
    //total a pagar
    const totalPagar = parseFloat(importe_cuota.value) * parseInt(cuotas);
    total_pagar.value = totalPagar.toFixed(2);
}

function resetTotales() {
    importe_cuota.value = '0.00';
    total_pagar.value = '0.00';
    interes_generado.value = '0.00';
}

function eliminarPrestamo(id) {
    Swal.fire({
        title: 'Esta seguro de anular?',
        text: "El prestamo no se eliminará de forma permanente, solo cambiará el estado a anulado!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "prestamos/eliminar/" + id;
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
                    t_prestamos.ajax.reload();
                }
            }

        }
    })
}

function listarPrestamos(nEstados) {
    $.ajax({
        url: base_url + 'prestamos/listar/' + nEstados,
        dataType: 'json',
        beforeSend: function () {
            var cardContainer = $('#cardContainer');
            cardContainer.empty();
            cardContainer.append('<div class="text-center" id="loadingMessage"><h>Cargando...!</h></div>');
        },
        success: function (data) {
            var cardContainer = $('#cardContainer');
            cardContainer.empty(); // Limpiamos el contenedor antes de agregar las tarjetas

            if (data.length === 0) {
                // Si no se encuentran préstamos, mostramos un mensaje
                var estadoTexto = obtenerEstadoTexto(nEstados);
                cardContainer.append('<div class="text-center" id="noDataMessage"><h3>No se han encontrado préstamos ' + estadoTexto + '.</h3></div>');
            } else {
                // Iteramos sobre los datos y creamos una tarjeta por cada préstamo
                data.forEach(function (prestamo) {
                    var badgeColor = obtenerColorEstadoPrestamo(prestamo);
                    var estadoTexto = obtenerTextoEstadoPrestamo(prestamo, nEstados);
                    var actionsHtml = generarAccionesHtml(prestamo);

                    var profileImagePath = base_url + prestamo.imgperfil;

                    var card = $('<div class="card mx-2 my-2">' +
                                 '<div class="card-body d-flex align-items-center">' +
                                     '<div style="flex: 1; text-align: center;">' +
                                         '<img src="' + profileImagePath + '" alt="Imagen de perfil" class="rounded-circle" style="width: 100px; height: 100px;">' +
                                     '</div>' +
                                     '<div style="flex: 2;">' +
                                         '<div style="height: 100%;">' +
                                             '<h5 class="nprestamo">Nº ' + prestamo.id + '</h5>' +
                                             '<h5 class="card-title">' + prestamo.nombre + ' ' + prestamo.apellido + '</h5>' +
                                             '<p class="card-text">Importe: ' + prestamo.importe + '</p>' +
                                             '<p class="card-text">Fecha V: ' + prestamo.vencimiento + '</p>' +
                                             '<p class="card-text">Interés: ' + prestamo.interes + '</p>' +
                                             '<span class="badge ' + badgeColor + '">' + estadoTexto + '</span>' +
                                             actionsHtml +
                                         '</div>' +
                                     '</div>' +
                                 '</div>' +
                             '</div>');

                    cardContainer.append(card); // Agregamos la tarjeta al contenedor
                });
            }
        }
    });
}

// Funciones auxiliares para obtener texto y colores según el estado del préstamo
function obtenerEstadoTexto(nEstados) {
    switch (nEstados) {
        case 'anulados':
            return 'anulados';
        case 'pagados':
            return 'pagados';
        case 'vencidos':
            return 'vencidos';
        case 'hoy':
            return 'cobrar hoy';
        case 'activos':
            return 'activos';
        default:
            return 'para su consulta';
    }
}

function obtenerColorEstadoPrestamo(prestamo) {
    if (parseInt(prestamo.cuotas_atrasadas) > 0) {
        return 'bg-danger';
    } else if (prestamo.t_estado == '0' || prestamo.t_estado == '4') {
        return 'bg-success';
    } else {
        return 'bg-warning';
    }
}

function obtenerTextoEstadoPrestamo(prestamo, nEstados) {
    var cuotasAtrasadas = parseInt(prestamo.cuotas_atrasadas);
    var cuotasPorPagar = parseInt(prestamo.cuotas_por_pagar);

    if (cuotasAtrasadas > 0) {
        return 'Préstamo vencido ' + cuotasAtrasadas + ' cuotas';
    } else if (nEstados == 'anulados') {
        return 'Crédito anulado';
    } else if (nEstados == 'pagados') {
        return 'Crédito pagado';
    } else if (cuotasPorPagar > 0) {
        return 'Crédito atrasado';
    } else {
        return 'Crédito al día';
    }
}

function generarAccionesHtml(prestamo) {
    var actionsHtml = '';
    if (prestamo.t_estado == '1' || prestamo.estado == '1' || prestamo.estado == '2' || prestamo.estado == '3') {
        actionsHtml += '<div class="text-center">' +
                        '<button class="btn btn-danger" onclick="eliminarPrestamo(' + prestamo.id + ', ' + prestamo.estado + ')"><i class="fas fa-trash"></i></button>' +
                        '<a href="' + base_url + 'prestamos/historial/' + btoa(prestamo.id) + '" class="btn btn-info"><i class="fas fa-eye"></i></a>' +
                      '</div>';
    } else if (prestamo.t_estado == '0' || prestamo.t_estado == '4') {
        actionsHtml += '<div class="text-center">' +
                        '<a href="' + base_url + 'prestamos/historial/' + btoa(prestamo.id) + '" class="btn btn-info"><i class="fas fa-eye"></i></a>' +
                      '</div>';
    }
    return actionsHtml;
}


function filtrar(nEstados) {
    //alert(nEstados);
    listarPrestamos(nEstados);
}
