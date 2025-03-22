<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;

class MapaController extends Controller
{
    public function index()
    {
        // Obtener datos con coordenadas y temperaturas
        $data = WeatherData::select('city', 'temperature', 'latitude', 'longitude')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('mapa', ['weatherData' => $data]);
    }
}
