<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherData;

class DashboardController extends Controller
{
    // Método index que filtra y muestra los datos en el dashboard
    public function index(Request $request)
{
    $query = WeatherData::query();

    if ($request->has('city') && $request->city != '') {
        $query->where('city', $request->city);
    }
    

    if ($request->has('from') && $request->has('to')) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    $weatherHistory = $query->orderBy('created_at', 'desc')->paginate(10);

    // Preparar datos
    $labels = $weatherHistory->pluck('created_at')->map(function($date) {
        return $date->format('d-m-Y H:i');
    })->toArray();

    $temps = $weatherHistory->pluck('temperature')->toArray();
    $humidity = $weatherHistory->pluck('humidity')->toArray();
    $windSpeed = $weatherHistory->pluck('wind_speed')->toArray();
    

    return view('dashboard', compact('weatherHistory', 'labels', 'temps', 'humidity', 'windSpeed'));
}
}
