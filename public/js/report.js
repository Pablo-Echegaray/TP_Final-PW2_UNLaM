const report = document.getElementById('report');
const modal_container = document.getElementById('modalContainer');
const close = document.getElementById('close');
const denegar = document.getElementById('denegar');


report.addEventListener('click', ()=> {
modal_container.classList.add('show');

});

close.addEventListener('click', ()=> {
modal_container.classList.remove('show');
});

denegar.addEventListener('click', ()=> {
modal_container.classList.remove('show');
});

let numero = 15;
function conteo() {
    document.getElementById('contador').innerText = `Lanzamiento en ${numero} segundos`;
    numero--;
    if (numero == 0) {
        clearInterval(lanzamiento);
        window.location.href = "http://localhost/TP_Final-PW2_UNLaM/partida/timerRefresh";
    }
}
let lanzamiento = setInterval(conteo, 1000);