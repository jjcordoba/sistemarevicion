// Obtener el modal
var modal = document.getElementById("myModal");

// Obtener el botón que abre el modal
var btn = document.getElementById("openModalBtn");

// Obtener el elemento <span> que cierra el modal
var span = document.getElementsByClassName("close")[0];

// Cuando el usuario haga clic en el botón, abre el modal
btn.onclick = function() {
    modal.style.display = "block";
}

// Cuando el usuario haga clic en <span> (x), cierra el modal
span.onclick = function() {
    modal.style.display = "none";
}

// Cuando el usuario haga clic en cualquier lugar fuera del modal, lo cierra
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Manejar el envío del formulario
document.getElementById("addCompanyForm").onsubmit = function(event) {
    event.preventDefault();

    // Obtener los datos del formulario
    var companyName = document.getElementById("companyName").value;
    var companyAddress = document.getElementById("companyAddress").value;
    var companyPhone = document.getElementById("companyPhone").value;

    // Aquí puedes hacer una solicitud AJAX para enviar los datos al servidor o simplemente mostrar un mensaje
    alert("Empresa agregada: " + companyName + ", " + companyAddress + ", " + companyPhone);

    // Cerrar el modal
    modal.style.display = "none";

    // Limpiar el formulario
    document.getElementById("addCompanyForm").reset();
}
