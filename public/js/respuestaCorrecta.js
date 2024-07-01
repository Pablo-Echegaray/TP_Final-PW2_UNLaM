document.addEventListener('DOMContentLoaded', function(){
const respuestas = document.querySelectorAll('.opcion');

respuestas.forEach(opcion =>{
    const estado = opcion.getAttribute('data-estado');
    console.log(estado);

    if(estado==='1'){
        opcion.classList.add('respuesta-correcta');
    } else {
        opcion.classList.add('respuesta-incorrecta');  // Opcional: aplicar clase para incorrectas
      }
});
});