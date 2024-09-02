document.addEventListener("DOMContentLoaded", function () {
    movimientos();
});

function movimientos() {
    const url = base_url + 'cajas/movimientos';
    //hacer una instancia del objeto XMLHttpRequest 
    const http = new XMLHttpRequest();
    //Abrir una Conexion - POST - GET
    http.open('GET', url, true);
    //Enviar Datos
    http.send();
    //verificar estados
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            console.log(res);
            var ctx = document.getElementById("reporteMovimiento").getContext('2d');
            myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Monto Inicial", "Ingresos", "Egresos", "Saldo"],
                    datasets: [{
                        backgroundColor: [
                            '#0c62e0',
                            '#e4ad07',
                            '#e20e22',
                            '#800080'
                        ],

                        hoverBackgroundColor: [
                            '#0c62e0',
                            '#e4ad07',
                            '#e20e22',
                            '#800080'
                        ],

                        data: [res.inicio, res.ingreso, res.egreso, res.saldo],
                        borderWidth: [1, 1, 1, 1, 1]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutoutPercentage: 0,
                    legend: {
                        position: 'bottom',
                        display: true,
                        labels: {
                            boxWidth: 8
                        }
                    },
                    tooltips: {
                        displayColors: false,
                    },
                }
            });
        }
    }

}