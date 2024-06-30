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
    document.getElementById('contador').innerText = `00:${numero}`;
    numero--;
    if (numero <= 9) {
        document.getElementById('contador').innerText = `00:0${numero}`;
    }
    if (numero == 0) {
        clearInterval(lanzamiento);
        window.location.href = "http://localhost/TP_Final-PW2_UNLaM/partida/timerRefresh";
    }
}
let lanzamiento = setInterval(conteo, 1000);