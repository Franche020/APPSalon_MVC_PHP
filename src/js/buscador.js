document.addEventListener('DOMContentLoaded', function() {
   iniciarApp()
})

function iniciarApp () {
    buscarFecha();
}

function buscarFecha() {
    const fechaInput = document.querySelector('#fecha');

    fechaInput.addEventListener('input', function (e) {
        const fechaSeleccionada = e.target.value;
        if (fechaSeleccionada==='') {
            var date = new Date();
            day = date.getUTCDay().toString().padStart(2, '0');
            month = (date.getMonth()+1).toString().padStart(2, '0');
            year = date.getFullYear().toString();
            
            date = `${year}-${month}-${day}`;
            window.location = `?fecha=${date}`;
            
            
        } else {
            window.location = `?fecha=${fechaSeleccionada}`;
        }
    })
}