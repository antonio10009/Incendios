<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\WeatherData;
use Illuminate\Support\Facades\Cache;

class ClimaController extends Controller
{
    public function mostrarClima(Request $request)
{
    $city = $request->input('city', 'Valparaíso'); // Por defecto Valparaíso
    $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'http://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city . ',CL', // Ciudad ingresada
                'appid' => '010812aedca4fcccc144a35e18d1c873', 
                'units' => 'metric',
                'lang' => 'es'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

    // Guardar datos en la base de datos
    WeatherData::create([
        'city' => $data['name'],
        'temperature' => $data['main']['temp'],
        'humidity' => $data['main']['humidity'],
        'pressure' => $data['main']['pressure'],
        'wind_speed' => $data['wind']['speed'],
        'description' => $data['weather'][0]['description']
    ]);

    return redirect('/dashboard')->with('success', "Datos de $city almacenados correctamente.");
}
}