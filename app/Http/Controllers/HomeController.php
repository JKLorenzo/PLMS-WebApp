<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', [
            'apiKey' => Config::get('app.maps_key'),
            'heatmapData' => Unit::where('status', 'fault')->get(['id', 'status', 'latitude', 'longitude'])
        ]);
    }
}
