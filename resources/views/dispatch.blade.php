@extends('layouts.app')

@section('head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <link href="{{ asset('libs/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />

    <script>
        let map, markers, bounds;

        const cadiz = {
            'lat': 10.95583493620157,
            'lng': 123.30611654802884
        };

        function initMap() {
            $(document).ready(() => {
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: cadiz,
                    mapTypeId: "roadmap",
                    styles: darkMode === "enabled" ? mapDark : mapLight,
                });

                markers = [];
            });
        }


        function updateMarker(units, id) {
            $('form').attr('action', `/incidents/${id}/dispatch`);

            var radio = document.getElementById(`radio${id}`);
            var nxtbtn = document.getElementById('nextbtn');

            if (radio.checked) {
                nxtbtn.disabled = false;
            } else {
                nxtbtn.disabled = true;
            }

            for (const marker of markers) {
                marker.setMap(null);
            }

            markers = [];

            bounds = new google.maps.LatLngBounds();

            for (const unit of units) {
                const marker = new google.maps.Marker({
                    map,
                    label: `${unit.id}`,
                    collisionBehavior: google.maps.CollisionBehavior.REQUIRED_AND_HIDES_OPTIONAL,
                    position: new google.maps.LatLng(parseFloat(unit.latitude), parseFloat(unit.longitude)),
                });

                markers.push(marker);
                bounds.extend(marker.getPosition());
            }

            map.setZoom(15);
            map.setCenter(units.length > 0 ? bounds.getCenter() : cadiz);

            if (units.length > 1) {
                google.maps.event.addListenerOnce(map, 'bounds_changed', () => {
                    const zoom = map.getZoom() - 1;
                    map.setZoom(zoom > 15 ? 15 : zoom);
                });

                google.maps.event.addListenerOnce(map, 'idle', () => {
                    window.setTimeout(() => {
                        map.fitBounds(bounds);
                    }, 1000);
                });
            }
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
@endsection

@section('content')
    <form action="" method="POST">
        @csrf
        @method('GET')
        <div class="container-fluid mt-2">
            <div class="card" style="margin-bottom: 6%">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <h4 class="header-title">Incidents</h4>
                            <p class="sub-header">
                                Please select an incident.
                            </p>

                            <table data-toggle="table" data-page-size="10" data-buttons-class="xs btn-light"
                                data-pagination="true" class="table-bordered">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th></th>
                                        <th>Incident ID</th>
                                        <th>Number of Units</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($incidents as $incident)
                                        <tr class="text-center">
                                            <td>

                                                <input type="radio" id="radio{{ $incident->id }}" class="radio"
                                                    onclick="updateMarker({{ $incident->units()->get() }}, {{ $incident->id }})"
                                                    name="incident_id">
                                            </td>
                                            <td>
                                                {{ $incident->id }}
                                            </td>
                                            <td>
                                                {{ count($incident->units()->get()) }}

                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($incident->created_at)->toDayDateTimeString() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div id="map" style="height: calc(75vh - 71px);" class="mb-3"></div>
                                <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap&v=beta&libraries=visualization"
                                                                async>
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-center d-flex mt-3">
                        <button id="nextbtn" type="submit" class="btn btn-primary px-5 py-1" onclick="nextbtn()"
                            disabled>Next</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; <span>Power Line Monitoring</span>
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-sm-block">
                        <a href="{{ route('about') }}">PLMS-CLZ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- end card-->
@endsection
@section('script')
    <script src="{{ subdirMix('js/vendor.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script src="{{ subdirMix('js/pages/bootstrap-tables.init.js') }}"></script>
@endsection
