<?php

namespace App\Http\Controllers;

use App\Events\ConsoleMessage;
use App\Events\HeatmapUpdate;
use App\Events\IncidentUpdate;
use App\Events\UnitUpdate;
use App\Models\Incident;
use App\Models\Unit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UnitsApiController extends Controller
{
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'ability:accessUnits']);

        $this->client = new Client([
            'base_uri' => 'https://maps.googleapis.com',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $phone_number
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $phone_number)
    {
        if (!$request->user()->tokenCan('editUnits'))
            return abort(401);

        $fields = $request->validate([
            'status' => 'required|string',
            'location' => 'required|string|starts_with:$GPRMC,',
        ]);

        $unit = Unit::where('phone_number', $phone_number)->first();

        if (!$unit) return abort(404);

        // Parse GPRMC NMEA Data
        list($id, $utc, $posStatus, $lat, $latDir, $lng, $lngDir, $gndSpeed, $trkTrue, $date, $magVar, $magVarDir) = explode(',', $fields['location']);

        // Latitude
        $latitude = $lat / 100;
        $latitude_degrees = explode('.', $latitude)[0];
        $latitude_minutes = $lat - ($latitude_degrees * 100);
        $latitude_seconds = ($latitude_minutes / 60);
        $latitude = $latitude_degrees + $latitude_seconds;
        if ($latDir == 'S') $latitude *= -1;

        // Longitude
        $longitude = $lng / 100;
        $longitude_degrees = explode('.', $longitude)[0];
        $longitude_minutes = $lng - ($longitude_degrees * 100);
        $longitude_seconds = ($longitude_minutes / 60);
        $longitude = $longitude_degrees + $longitude_seconds;
        if ($lngDir == 'W') $longitude *= -1;

        try {
            $response = $this->client->request('GET', '/maps/api/geocode/json', [
                'query' => [
                    'latlng' => "$latitude,$longitude",
                    'key' => Config::get('app.maps_key')
                ]
            ]);

            $data = json_decode($response->getBody()->getContents());

            if ($data->status == 'OK' && count($data->results) > 0) {
                $results = collect($data->results);

                function getComponent($components, $type)
                {
                    return $components->filter(function ($component) use ($type) {
                        return in_array($type, $component->types);
                    })->map(function ($component) {
                        return $component->long_name;
                    })->first();
                }

                $components = collect();

                $address = $results->map(function ($result) {
                    return collect($result->address_components);
                });

                foreach ($address as $_components) {
                    foreach ($_components as $_component) {
                        $components->add($_component);
                    }
                }

                $unit->address()->update([
                    'street' => getComponent($components, 'route'),
                    'barangay' => getComponent($components, 'administrative_area_level_5'),
                    'city' => getComponent($components, 'administrative_area_level_3'),
                    'region' => getComponent($components, 'administrative_area_level_2'),
                ]);
            }
        } catch (GuzzleException $error) {
            event(new ConsoleMessage($error, true));
        }

        // Update unit
        $unit->update([
            'status' => $fields['status'],
            'latitude' => doubleval($latitude),
            'longitude' => doubleval($longitude),
        ]);

        // Log changes
        $unit->logs()->create([
            'status' => $fields['status'],
        ]);

        // Broadcast events
        event(new UnitUpdate($unit));
        event(new HeatmapUpdate($unit));

        // Get unit's latest incident
        $incident = $unit->latestIncident();

        if ($fields['status'] == 'normal' && $incident && !$incident->resolved) {
            // Resolve incident if all units are normal
            if (count($incident->units()->where('status', 'fault')->get()) == 0) {
                $incident->info()->create([
                    'title' => 'Resolved',
                    'description' => 'This incident has been resolved.'
                ]);

                $incident->update([
                    'resolved' => true
                ]);

                event(new IncidentUpdate($incident));
            }
        } else if ($fields['status'] == 'fault') {
            // Get recent incident
            $incident = Incident::query()->latest()->firstOrCreate([
                'resolved' => false,
            ]);

            // Get unit address
            $unitAddress = $unit->address()->first();

            // Check if incident has info
            if (count($incident->info()->get()) > 0) {
                // Get matching locations
                $locations = collect($incident->locations)->where('city', $unitAddress->city)->filter(function ($location) use ($unitAddress) {
                    return $location['barangays']->contains($unitAddress->barangay);
                });

                // Check if location is not yet added in the incident
                if (count($locations) == 0) {
                    // Create incident info update for current location
                    $incident->info()->create([
                        'title' => 'Outage Detected',
                        'description' => "We have also detected a power outage at $unitAddress->barangay."
                    ]);
                }
            } else {
                // Create incident info
                $incident->info()->create([
                    'title' => 'Outage Detected',
                    'description' => "We have detected a power outage at $unitAddress->barangay."
                ]);
            }

            // Attach unit to incident
            $incident->units()->attach($unit->id);
        }

        return $unit;
    }

    public function heatmap(Request $request)
    {
        return Unit::where('status', 'fault')->get(['id', 'status', 'latitude', 'longitude']);
    }
}
