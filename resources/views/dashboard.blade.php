<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Clima</title>
    <!-- CSS opcional -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1, h2, h3 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        canvas {
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
        }
        .button {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Clima</h1>
        <h2>Histórico de Datos Meteorológicos</h2>

        <!-- Formulario para consultar una ciudad -->
        <form method="GET" action="/clima">
            <label for="city">Ciudad:</label>
            <input type="text" name="city" placeholder="Ej: Valparaíso" required>
            <button type="submit">Consultar</button>
        </form>

        <!-- Formulario para filtrar por rango de fechas -->
        <form method="GET" action="/dashboard">
            <label for="from">Desde:</label>
            <input type="date" name="from" required>
            <label for="to">Hasta:</label>
            <input type="date" name="to" required>
            <button type="submit">Filtrar</button>
        </form>

        <!-- Gráfico de Temperatura -->
        <h3>Temperatura (°C)</h3>
        <canvas id="tempChart"></canvas>

        <!-- Gráfico de Humedad -->
        <h3>Humedad (%)</h3>
        <canvas id="humidityChart"></canvas>

        <!-- Gráfico de Velocidad del Viento -->
        <h3>Velocidad del Viento (m/s)</h3>
        <canvas id="windChart"></canvas>

        <!-- Botón para Ver Mapa -->
        <a href="/mapa" class="button">Ver Mapa</a>

        <!-- Tabla de Datos -->
        <h3>Datos Registrados</h3>
        <table>
            <thead>
                <tr>
                    <th>Ciudad</th>
                    <th>Temperatura (°C)</th>
                    <th>Humedad (%)</th>
                    <th>Presión (hPa)</th>
                    <th>Velocidad del Viento (m/s)</th>
                    <th>Descripción</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weatherHistory as $weather)
                    <tr>
                        <td>{{ $weather->city }}</td>
                        <td>{{ $weather->temperature }}</td>
                        <td>{{ $weather->humidity }}</td>
                        <td>{{ $weather->pressure }}</td>
                        <td>{{ $weather->wind_speed }}</td>
                        <td>{{ $weather->description }}</td>
                        <td>{{ $weather->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Agregar Chart.js desde un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts para gráficos -->
    <script>
    const labels = @json($labels);

    // Gráfico de Temperatura
    const tempCtx = document.getElementById('tempChart').getContext('2d');
    new Chart(tempCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Temperatura (°C)',
                data: @json($temps),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: false,
                tension: 0.1
            }]
        }
    });

    // Gráfico de Humedad
    const humidityCtx = document.getElementById('humidityChart').getContext('2d');
    new Chart(humidityCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Humedad (%)',
                data: @json($humidity),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico de Velocidad del Viento
    const windCtx = document.getElementById('windChart').getContext('2d');
    new Chart(windCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Velocidad del Viento (m/s)',
                data: @json($windSpeed),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false,
                tension: 0.1
            }]
        }
    });
    </script>
</body>
</html>
