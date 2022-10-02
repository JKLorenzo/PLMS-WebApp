<?php

namespace App\Http\Controllers;

use App\Events\IncidentUpdate;
use App\Models\Incident;
use App\Models\IncidentInfo;
use App\Models\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class IncidentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('incidents', [
            'apiKey' => Config::get('app.maps_key'),
            'incidents' => Incident::orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unit_ids = Unit::where('status', 'fault')->get()->filter(function ($unit) {
            return $unit->isUntracked();
        })->map(function ($unit) {
            return $unit->id;
        })->toArray();

        return view('create-incidents', [
            'apiKey' => Config::get('app.maps_key'),
            'units' => Unit::whereIn('id', $unit_ids)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $fields = $request->validate([
            'title' => 'required|string|min:1',
            'description' => 'required|string|min:1',
            'unit_ids' => 'required|array|min:1'
        ]);

        $incident = Incident::create([
            'resolved' => false,
        ]);

        $incident->info()->create([
            'title' => $fields['title'],
            'description' => $fields['description']
        ]);

        $units = Unit::whereIn('id', array_keys($fields['unit_ids']))->where('status', 'fault')->get();

        $incident->units()->sync(array_column($units->toArray(), 'id'));

        return redirect()->back();
    }

    public function destroy($id)
    {
        $destroy = Incident::where('id', $id)->delete();

        return redirect()->back();
    }

    public function edit($id)
    {
        $incident = Incident::find($id);

        $info = $incident->info()->get();

        return $info;
    }

    public function update(Request $request, $incidentId, $infoId)
    {

        $incident = Incident::find($incidentId);

        $info  = $incident->info()->where('id', $infoId)->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        $info = $incident->info()->where('id', $infoId)->get();

        event(new IncidentUpdate($incident));

        return $info;
    }

    public function add(Request $request, $id)
    {
        $fields = $request->validate([
            'title' => 'required|string|min:1',
            'description' => 'required|string|min:1'
        ]);

        $incident = Incident::find($id);

        $info = $incident->info()->create([
            'title' => $fields['title'],
            'description' => $fields['description']
        ]);

        event(new IncidentUpdate($incident));

        return $info;
    }

    public function info($id)
    {
        $info = IncidentInfo::where('id', $id)->get();

        return $info;
    }
}
