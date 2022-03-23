@extends('layouts.app')

@section('head')
    <script>
        let map, heatmap, heatmapData;
        let _heatmapData = <?php print_r($heatmapData); ?>;
        console.log(_heatmapData);

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: {
                    'lat': 10.95583493620157,
                    'lng': 123.30611654802884
                },
                mapTypeId: "roadmap"
            });

            // Initialize heatmap data
            heatmapData = new google.maps.MVCArray();

            // Initialize heatmap layer
            heatmap = new google.maps.visualization.HeatmapLayer({
                data: heatmapData,
                radius: 50,
                gradient: [
                    "rgba(0, 0, 0, 0)",
                    "rgba(255, 0, 0, 0.6)",
                    "rgba(255, 0, 0, 0.7)",
                    "rgba(255, 0, 0, 0.8)",
                    "rgba(255, 0, 0, 0.9)",
                    "rgba(255, 0, 0, 1)"
                ],
                maxIntentsity: 1
            });

            // Link heatmap with map
            heatmap.setMap(map);
            for (const data of {!!$heatmapData!!}) {
                heatmapData.push({
                    id: data.id,
                    weight: 1,
                    location: new google.maps.LatLng(data.latitude, data.longitude)
                });
            }

            Echo.channel("Home").listen("HeatmapUpdate", updateHeatmap);
        }
        async function updateHeatmap(data) {
            if (data.status == "normal") {
                // Loop through all the elements
                for (let i = 0; i < heatmapData.getLength(); i++) {
                    const thisData = heatmapData.getAt(i);
                    // Remove element if the phone number matches
                    if (thisData.id === data.id) {
                        heatmapData.removeAt(i);
                        break;
                    }
                }
            } else if (data.status == "fault") {
                // Construct the data
                const value = {
                    id: data.id,
                    weight: 1,
                    location: new google.maps.LatLng(data.latitude, data.longitude),
                };

                // Get the current index if existing
                let currentIndex;
                for (let i = 0; i < heatmapData.getLength(); i++) {
                    const thisData = heatmapData.getAt(i);
                    if (thisData.id === data.id) {
                        currentIndex = i;
                        break;
                    }
                }

                // Remove existing data
                if (typeof currentIndex === "number") {
                    heatmapData.removeAt(currentIndex);
                }

                // Push the new data
                heatmapData.push(value);
            }
        }
    </script>

    <style>
        body {
            overflow-y: hidden;
        }

    </style>
@endsection

@section('content')
    <div id="map" style="height: calc(100vh - 71px);"></div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap&v=weekly&libraries=visualization"
        async>
    </script>
@endsection

@section('script')
    <script src="{{ asset('js/vendor.min.js') }}"></script>
@endsection
