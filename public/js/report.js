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