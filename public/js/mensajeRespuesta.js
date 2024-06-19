document.addEventListener('DOMContentLoaded', function() {
    var submitAnswer = document.getElementById('submit-answer');
    var messageContainer = document.getElementById('messageContainer');

    if (submitAnswer) {
        submitAnswer.addEventListener('submit', function(event) {
            event.preventDefault();

            fetch('/TP_Final-PW2_UNLaM/partida/checkAnswer', {
                method: 'POST',
                body: new FormData(event.target)
            })
            .then(response => response.json())
            .then(data => {
                var mensaje = Mustache.render(messageTemplate, { mensaje: data.mensaje });
                showMessage(mensaje);
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Hubo un error al procesar la respuesta.');
            });
        });
    } 

    function showMessage(mensaje) {
        messageContainer.textContent = mensaje;
        messageContainer.style.display = 'block';

        setTimeout(function() {
            messageContainer.style.display = 'none';
        }, 3000);
    }
});

//----------------------------------------------------------------