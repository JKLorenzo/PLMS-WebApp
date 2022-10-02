@extends('layouts.app')


@section('head')
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
    <script>
        let map, markers, bounds, infoWindow;

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

                // Close button
                const controlDiv = document.createElement("div");
                const controlUI = document.createElement("div");
                const controlText = document.createElement("div");

                // Set CSS for the control border.
                controlUI.style.backgroundColor = "#fff";
                controlUI.style.border = "2px solid #fff";
                controlUI.style.borderRadius = "3px";
                controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.1)";
                controlUI.style.cursor = "pointer";
                controlUI.style.marginTop = "10px";
                controlUI.style.marginRight = "10px";
                controlUI.style.width = "40px";
                controlUI.style.textAlign = "center";
                controlUI.title = "Close";
                controlDiv.appendChild(controlUI);

                // Set CSS for the control interior
                controlText.style.color = "rgb(25,25,25)";
                controlText.style.padding = "4px";
                controlText.innerHTML = `<i class="mdi mdi-close fs-3"></i>`;
                controlUI.appendChild(controlText);

                controlUI.addEventListener("click", () => {
                    $('#map-modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });

                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
            });
        }

        function updateMarkers(units) {
            bounds = new google.maps.LatLngBounds();
            infoWindow = new google.maps.InfoWindow();

            for (const marker of markers) {
                marker.setMap(null);
            }

            markers = [];

            for (const unit of units) {
                const marker = new google.maps.Marker({
                    map,
                    label: `${unit.id}`,
                    collisionBehavior: google.maps.CollisionBehavior.REQUIRED_AND_HIDES_OPTIONAL,
                    position: new google.maps.LatLng(parseFloat(unit.latitude), parseFloat(unit.longitude)),
                });

                marker.addListener("click", () => {
                    infoWindow.setContent(`${unit.formatted_address}`);
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
                bounds.extend(marker.getPosition());
            }

            setTimeout(() => {
                map.setZoom(15);
                map.setCenter(units.length > 0 ? bounds.getCenter() : cadiz);
            }, 1000);

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
@endsection

@section('content')
    <div class="container-fluid pt-3 mb-5">
        @if (count($incidents) <= 0)
            <div class="container-fluid" style="margin-top: 17%">
                <div class="justify-content-center d-flex">
                    <i class="fe-calendar fs-1 text-dark"></i>
                </div>
                <div class="justify-content-center d-flex">
                    <span class="h4 text-primary">No published incidents.</span>
                </div>
            </div>
        @endif
        <div class="row">
            @foreach ($incidents as $incident)
                <span class="anchor" id="{{ $incident->id }}"></span>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body shadow">
                            @auth
                                <div class="dropdown float-end">

                                    <a id="dotIcon{{ $incident->id }}" href="#" class="dropdown-toggle card-drop arrow-none"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                                    </a>
                                    <div id="cancelbtn{{ $incident->id }}"></div>


                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item text-success" href="#"
                                            onclick="addIncident({{ $incident->id }})"><i
                                                class="mdi mdi-plus-box fs-5 pe-1"></i><span>Add Info</span></a>
                                        <a class="dropdown-item text-info" href="#"
                                            onclick="editIncident({{ $incident->id }})"><i
                                                class="fe-edit pe-1"></i><span>Edit Info</span></a>
                                        <a class="dropdown-item text-danger" type="button"
                                            onclick="deleteIncident('{{ route('incident.delete', ['incident' => $incident->id]) }}')" data-bs-toggle="modal"
                                            data-bs-target="#RemoveIncident">
                                            <i class="fe-delete pe-1"></i><span>Remove</span></a>
                                    </div>
                                </div> <!-- end dropdown -->
                            @endauth

                            <a href="#{{ $incident->id }}" class="text-muted">
                                <h5 class="mt-0">
                                    <span class="text-secondary">
                                        {{ \Carbon\Carbon::parse($incident->created_at)->toDayDateTimeString() }}
                                    </span>
                                </h5>
                            </a>

                            <hr>

                            @foreach ($incident->info as $info)
                                <span class="anchor" id="{{ $incident->id }}-{{ $info->id }}"></span>

                                <div>
                                    <a href="#{{ $incident->id }}-{{ $info->id }}" class="text-muted">
                                        <span id="title{{ $info->id }}" class="text-capitalize fw-bolder h5 mt-0">
                                            {{ $info->title }}
                                            <i class="fe-minus"></i>
                                        </span>
                                    </a>
                                    <span id="input-description{{ $info->id }}" class="">
                                        {{ $info->description }}
                                    </span>
                                    <div id="_editIcon{{ $info->id }}" class="float-end"></div>
                                </div>

                                <p class="mt-2">
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::parse($info->created_at)->toDayDateTimeString() }}
                                    </span>
                                </p>
                            @endforeach

                            <hr>

                            <span class="text-capitalize fw-bold h5 mt-0">
                                Areas Affected: <a type="button" onclick="updateMarkers({{ $incident->units }})"
                                    data-bs-toggle="modal" data-bs-target="#map-modal" class="text-info"><i
                                        type="button" class="fe-map fs-5" title="View Map" tabindex="0"
                                        data-plugin="tippy" data-tippy-placement="right"></i></a>
                            </span>
                            <ul>
                                @foreach ($incident->locations as $location)
                                    <li>
                                        <a class="text-muted">
                                            <span class="text-capitalize h6">
                                                {{ $location['city'] }}
                                                <i class="fe-minus"></i>
                                            </span>
                                        </a>

                                        <span class="fs-6">
                                            {{ implode(', ', $location['barangays']->toArray()) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div> <!-- end card box-->
                </div><!-- end col-->
            @endforeach
        </div>
    </div>

    <script src="{{ subdirMix('js/incidents.js') }}"> </script>

    @auth
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
        </footer>
    @endauth
    @include('modals.incidents')
@endsection

@section('script')
    <script src="{{ subdirMix('js/vendor.min.js') }}"></script>
@endsection
