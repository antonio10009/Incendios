<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Zonas de Riesgo</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <style>
        #map {
            height: 80vh;
            margin: 20px auto;
            border: 1px solid #ccc;
        }
        .container {
            text-align: center;
        }
        .legend {
            background: white;
            padding: 10px;
            position: absolute;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mapa de Zonas de Riesgo</h1>
        <p>Este mapa muestra zonas con riesgo de incendio debido a altas temperaturas.</p>
        <a href="/dashboard" class="button">Volver al Dashboard</a>
        <div id="map"></div>
    </div>

    <!-- Audio de alerta -->
    <audio id="alertSound" src="/audio/alerta.mp3"></audio>

    <script>
        // Inicializar el mapa centrado en Chile
        const map = L.map('map').setView([-33.0458, -71.6197], 7);

        // Cargar el mapa base desde OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Datos simulados desde el servidor (enviados por el controlador)
        const weatherData = @json($weatherData);

        // Obtener referencia al audio de alerta
        const alertSound = document.getElementById('alertSound');
        let alertTriggered = false; // Para evitar reproducir el sonido múltiples veces

        // Función para determinar el color del círculo según la temperatura
        const getCircleOptions = (temperature) => {
            return {
                color: temperature > 35 ? 'red' : 'blue',
                fillColor: temperature > 35 ? 'red' : 'blue',
                fillOpacity: 0.5,
                radius: 2000 // Radio en metros
            };
        };

        // Agregar círculos al mapa
        weatherData.forEach(data => {
            const { city, temperature, latitude, longitude } = data;

            if (latitude && longitude) {
                const options = getCircleOptions(temperature);

                // Mostrar advertencia si la temperatura es alta
                if (temperature > 35 && !alertTriggered) {
                    alertTriggered = true; // Evitar múltiples reproducciones
                    alertSound.play(); // Reproducir sonido de alerta
                    alert('¡Advertencia! Zona de alto riesgo detectada: ' + city);
                }

                L.circle([latitude, longitude], options)
                    .bindPopup(`<b>${city}</b><br>Temperatura: ${temperature} °C`)
                    .addTo(map);
            }
        });

        // Agregar leyenda
        const legend = L.control({ position: 'bottomleft' });

        legend.onAdd = function () {
            const div = L.DomUtil.create('div', 'legend');
            div.innerHTML = `
                <h4>Riesgo de Incendio</h4>
                <p><span style="background:red; width: 10px; height: 10px; display:inline-block;"></span> Alto riesgo (Temp > 35°C)</p>
                <p><span style="background:blue; width: 10px; height: 10px; display:inline-block;"></span> Sin riesgo</p>
            `;
            return div;
        };

        legend.addTo(map);
    </script>
</body>
</html>
