<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Unit;
use App\Models\Lineman;
use App\Notifications\Dispatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class DispatchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dispatch', [
            'apiKey' => Config::get('app.maps_key'),
            'incidents' => Incident::where('resolved', false)->get(),
            'linemen' => Lineman::all(),
            'units' => Unit::where('status', 'fault')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function _dispatch(Request $request, $id)
    {
        $incident = Incident::find($id);

        $assigned_linemen = $incident->linemen()->get();


        $linemen = Lineman::all();

        return view('dispatch-linemen', [
            'linemen' => $linemen,
            'incident_id' => $id,
            'assigned_linemen' => $assigned_linemen
        ]);
    }
    public function store(Request $request, $id)
    {

        $fields = $request->validate([
            'lineman_ids' => 'required|array|min:1',
        ]);


        $incident = Incident::find($id);

        $linemen = Lineman::whereIn('id', array_keys($fields['lineman_ids']))->get();

        $incident->linemen()->syncWithoutDetaching(array_keys($fields['lineman_ids']));

        $units = $incident->units()->get();

        Notification::send($linemen, new Dispatch($units));

        return redirect('/dispatch');
    }
}
