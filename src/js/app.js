let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

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