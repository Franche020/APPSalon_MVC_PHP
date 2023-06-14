let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;
const diasCerrado = [0,6];
const cita = {
    usuarioId: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

// Inicializar cuando todo el DOM esté cargado
document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});


function iniciarApp() {
    
    mostrarSeccion();
    tabs(); // Cambia la seccion cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaAnterior();
    paginaSiguiente();

    consultarAPI(); // Consulta la API en el backend de php

    idCliente();
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    seleccionarHora(); // Añade la hora de la cita en el objeto

    mostrarResumen(); // Muestra el resumen de la cinta
}


function mostrarSeccion() {
    // Ocultar a las que tengan la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual a las que ya la tengan
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);  
    tab.classList.add('actual')
}

function botonesPaginador () {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso ===3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();
}


 //* ############## EVENTOS #######################


function tabs () {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach (
        boton=>{
            boton.addEventListener('click',function(e){
                paso = parseInt(e.target.dataset.paso);
                mostrarSeccion();
                botonesPaginador();
            })
        }
    )
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', ()=>{
        if (paso<=pasoInicial){
            return;
        }
        paso --;
        botonesPaginador();
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', ()=>{
        if (paso>=pasoFinal){
            return;
        }
        paso ++;
        botonesPaginador();
    })
}


//* ##################### API ######################

async function consultarAPI() {

    try {
        const url = 'http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
        
    } catch(error) {
        console.log(error);
    }
};

//* ################ SERVICIOS ###############


function mostrarServicios (servicios) {
    servicios.forEach(servicio=> {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `${precio}€`;
        
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function (){
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function idCliente () {
    cita.usuarioId = document.querySelector('#usuarioId').value;
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;

    
}

function seleccionarServicio (servicio) {
    const {id} = servicio;
    const {servicios} = cita;

    const servicioDiv = document.querySelector(`[data-id-servicio="${id}"]`);
    // Comprobar si un servicio ya fue agregado o hay que quitarlo
    if (servicios.some(agregado => agregado.id === id)) {
        // eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);  
        servicioDiv.classList.remove('seleccionado'); 
    } else {
        // agregarlo
        cita.servicios = [...servicios, servicio];
        servicioDiv.classList.add('seleccionado'); 
    }

}

function seleccionarFecha () {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {
       const dia = new Date(e.target.value).getUTCDay();
       if (diasCerrado.includes(dia)){
        e.target.value = '';
        mostrarAlerta('Fines de semana no permitidos','error','.formulario');
       } else {
        cita.fecha = e.target.value;
       }
    })
}
function seleccionarHora () {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', (e)=> {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora <= 10 || hora >= 20 ) {
            mostrarAlerta('La hora no es valida','error','.formulario');
        } else {
            cita.hora = horaCita;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){ 

    // Previene que se generen mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia){
        console.log(alertaPrevia)
        alertaPrevia.remove();
    }

    // Scripting para generar la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        // Eliminacion de la alerta tras pasar un tiempo
        setTimeout(()=>{
            alerta.remove();
        },3000)
    }
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar contenido resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos de servicios, fecha u hora','error','.contenido-resumen', false);


        return;
    }

    // Heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de servicios';
    resumen.appendChild(headingServicios);
    
    // Iterando y mostrando los servicios
    cita.servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>${precio}€`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        
        resumen.appendChild(contenedorServicio);
    })

    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    // Formatear el div de resumen

    const {nombre, fecha, hora ,servicios} = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML= `<span>Nombre: </span>${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day:'numeric' }
    fechaFormateada = fechaObj.toLocaleString('es-ES', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML= `<span>Fecha: </span>${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML= `<span>Hora: </span>${hora}`;

    // Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = "Reservar Cita";
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    
    resumen.appendChild(botonReservar);
}

async function reservarCita(){

    // Extraer del objeto cita
    const {usuarioId,nombre, fecha, hora, servicios} = cita;
    
    // extraer ID servicios
    const idServicios = servicios.map(servicio => servicio.id);

    // Esto es como el submit del formulario
    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', usuarioId);
    datos.append('servicios', idServicios);

    try {
        // peticion hacia la api, envio de datos
        const url = 'http://localhost:3000/api/citas';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        // Lectura de la respuesta del servidor
        const resultado = await respuesta.json();

        if (resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            }).then(()=>{
                setTimeout(()=>{
                    window.location.reload();
                },3000)
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Hubo un error!',
          })
    }

}

// TODO Valiadacion PHP de fecha para que sea a partir del dia siguiente al actual.