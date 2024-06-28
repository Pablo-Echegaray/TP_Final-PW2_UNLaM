function drawPreguntasChart() {
    $.ajax({
        url: '/TP_Final-PW2_UNLaM/admin/questions',
        dataType: 'json',
        success: function(data) {
            var chartPath = data.chartPath;
            $('#preguntasChart').html('<img src="' + chartPath + '">');
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener datos del gráfico de preguntas:', error);
        }
    });
}

function drawJugadoresPorPaisChart() {
    $.ajax({
        url: '/TP_Final-PW2_UNLaM/admin/usersByCountry',
        dataType: 'json',
        success: function(data) {
            var chartPath = data.chartPath;
            $('#jugadoresPaisChart').html('<img src="' + chartPath + '">');
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener datos del gráfico de jugadores por país:', error);
        }
    });
}


function drawJugadoresPorSexoChart() {
    $.ajax({
        url: '/TP_Final-PW2_UNLaM/admin/usersBySex',
        dataType: 'json',
        success: function(data) {
            var chartData = google.visualization.arrayToDataTable(data.chartData);

            var options = {
                title: 'Usuarios por Sexo',
                pieHole: 0.4
            };

            var chart = new google.visualization.PieChart(document.getElementById('jugadoresSexoChart'));
            chart.draw(chartData, options);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener datos de usuarios por sexo:', error);
        }
    });
}

function showContent(option) {
    var contentContainer = $('#chart-container');

    contentContainer.empty();

    switch (option) {
        case 'preguntas':
            contentContainer.html('<h3>Gráfico de Preguntas</h3><div id="preguntasChart"></div>');
            drawPreguntasChart();
            break;
        case 'jugadores-pais':
            contentContainer.html('<h3>Gráfico de Jugadores por País</h3><div id="jugadoresPaisChart"></div>');
            drawJugadoresPorPaisChart();
            break;
        case 'jugadores-sexo':
            contentContainer.html('<h3>Gráfico de Jugadores por Sexo</h3><div id="jugadoresSexoChart"></div>');
            drawJugadoresPorSexoChart();
            break;
        default:
            break;
    }
}