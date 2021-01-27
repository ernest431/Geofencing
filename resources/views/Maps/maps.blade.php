@extends('layouts.master')

<!-- CSS -->
@section('css')
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.css" rel="stylesheet" />
    <style>
        .no-border {
            border: 0;
            box-shadow: none; 
        }

        .marker-patient {
          background-image: url("{{ URL::asset('assets/img/icons/Patient.png') }}");
          background-size: cover;
          width: 50px;
          height: 50px;
          border-radius: 50%;
          cursor: pointer;
        }

        .marker-nurse {
          background-image: url("{{ URL::asset('assets/img/icons/Nurse.png') }}");
          background-size: cover;
          width: 50px;
          height: 50px;
          border-radius: 50%;
          cursor: pointer;
        }

        .mapboxgl-popup {
          max-width: 200px;
        }

        .mapboxgl-popup-content {
          text-align: center;
          font-family: 'Open Sans', sans-serif;
        }
    </style>
@stop

<!-- Header Tabel Pasien -->
@section('header')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Map</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Maps</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Map</li>
                </ol>
              </nav>
            </div>
            <!-- <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral">New</a>
              <a href="#" class="btn btn-sm btn-neutral">Filters</a>
            </div> -->
          </div>
        </div>
      </div>
    </div>
@endsection

<!-- Content -->
@section('content')
      <div class="row">
        <div class="col">
          <div class="card border-0">
              <div class="card-header">
                  <div class="pull-left">
                    <h3 class="mb-0 card-title"><b>Lokasi Pasien</b></h3>
                  </div>
                  <div class="pull-right">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- Set Radius -->
                          <div class="form-group">
                              <div class="input-group">
                                  <!-- Input Radius -->
                                  <input type="text" id="radiusText" class="form-control no-border only-number" autocomplete="off" placeholder="Radius Meter">
                                  <div class="input-group-prepend">
                                      <button type="button" id="setRadius" class="btn btn-info"><i class="fa fa-map-marker"></i></button>
                                  </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <button id="delete" type="button" style="height: 46px;" class="btn btn-danger"><i class="ni ni-fat-remove"></i></button>
                          <button id="tracklog" type="button" style="height: 46px;" class="btn btn-info"><i class="fa fa-paw"></i><b>Track Log</b></button>
                        </div>
                      </div>
                  </div>
              </div>
              <!-- Input hide -->
              <input type="hidden" id="latitude">
              <input type="hidden" id="longitude">
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <div id="distance" class="distance-container"></div>
                      <div id="map" class="map-canvas"></div>
                      <!-- <button id="tracklog" type="button" style="height: 46px;" class="btn btn-info"><i class="fa fa-paw"></i><b>Track Log</b></button> -->
                    </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
@endsection

@section('js')
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script> -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.js'></script>
    <script src="https://www.gstatic.com/firebasejs/7.15.5/firebase-app.js"></script>
    <script src="https://npmcdn.com/@turf/turf@5.1.6/turf.min.js"></script>
    <script>
        // API Mapbox
        mapboxgl.accessToken = 'pk.eyJ1IjoiYXJ0aHVycnIiLCJhIjoiY2s3aXNna2F0MG9tYzNybzkwMjI3aDBxZSJ9.T0Ovsp5LBJVymtTJIMRVzg'

        // Deklarasi       
        var speedFactor = 30; // number of frames per longitude degree
        var animation; // to store and cancel the animation
        var startTime = 0;
        var progress = 0; // progress = timestamp - startTime
        var resetTime = false; // indicator of whether time reset is needed for the animation
        var distanceContainer = document.getElementById('distance');
        var lat = $('#latitude').val();
        var long = $('#longitude').val();

        // Constanta
        const metersToPixelsAtMaxZoom = (meters, latitude) =>
        meters / 0.075 / Math.cos(latitude * Math.PI / 180)

        // Create Map
        var map = new mapboxgl.Map({
          container: 'map',
          style: 'mapbox://styles/mapbox/streets-v11',
          center: [107.528928, -6.884589],
          zoom: 16
        });

        // Location
        var geometry = {
            'type' : 'Point',
            'properties' : {},
            'type' : 'Feature',
            'coordinates' : [107.528928, -6.884589]
        }

        // GeoJSON object to hold our measurement features
        var linejson = {
            'type': 'FeatureCollection',
            'features': []
        };

        // Used to draw a line between points
        var linestring = {
            'type': 'Feature',
            'geometry': {
                'type': 'LineString',
                'coordinates': []
            }
        };

        var markerPatient = {
          type: 'FeatureCollection',
          features: 
            {
              // Patient
              type: 'Feature',
              geometry: {
                type: 'Point',
                coordinates: [0, 0]
              },
              properties: {
                title: 'Pasien',
                description: 'Pasien'
              }
            }
        }

        // Create Marker Patient
        var el = document.createElement('div');
        el.className = 'marker-patient';
        
        // make a marker for each feature and add to the map
        var patient = new mapboxgl.Marker(el)
                      .setLngLat(markerPatient.features.geometry.coordinates)

                      .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
                      .setHTML('<h3>' + markerPatient.features.properties.title + '</h3><p>' + markerPatient.features.properties.description + '</p>'))
                      .addTo(map);

        // Marker
        var actor = {
          type: 'FeatureCollection',
          features: 
          [
            {
              // Perawat
              type: 'Feature',
              geometry: {
                type: 'Point',
                coordinates: [107.526739, -6.882906]
              },
              properties: {
                title: 'Perawat',
                description: 'Perawat 1'
              }
            },
            {

              // Perawat
              type: 'Feature',
              geometry: {
                type: 'Point',
                coordinates: [107.525044, -6.885547]
              },
              properties: {
                title: 'Perawat',
                description: 'Perawat 2'
              }
            },
            {

              // Perawat
              type: 'Feature',
              geometry: {
                type: 'Point',
                coordinates: [107.528005, -6.886378]
              },
              properties: {
                title: 'Perawat',
                description: 'Perawat 3'
              }
            },
            {

              // Perawat
              type: 'Feature',
              geometry: {
                type: 'Point',
                coordinates: [107.532103, -6.886293]
              },
              properties: {
                title: 'Perawat',
                description: 'Perawat 4'
              }
            },
            {

              // Perawat
              type: 'Feature',
              geometry: {
                type: 'Point',
                coordinates: [107.530644, -6.880520]
              },
              properties: {
                title: 'Perawat',
                description: 'Perawat 5'
              }
            }
          ]
        };

        var geojson = {
            'type': 'FeatureCollection',
            'features': [
                  {
                  'type': 'Feature',
                  'geometry': {
                    'type': 'LineString',
                    'properties': {},
                    'coordinates': [
                        @if(!empty($subjectTracklog))
                          @foreach($subjectTracklog as $location)
                            [ {{ $location['longitude'] }}, {{ $location['latitude'] }} ],
                          @endforeach
                        @endif
                      ]
                  }
              }
            ]
        };

        // Map On.load
        map.on('load', function() {

            // Add Point
            map.addSource('rumah-pasien', {
                'type': 'geojson',
                'data': {
                  'type': 'FeatureCollection',
                  'features': [{
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [107.528928, -6.884589]
                      }
                    },
                  ]
                }
            });

            // Lokasi Pasien
            map.addSource('marker-pasien', {
              'type' : 'geojson',
              'data' : geometry
            });

            // Add Track Log  
            map.addSource('TrackLog', {
              'type': 'geojson',
              'data': geojson
            });

            // Add Distance
            map.addSource('linejson', {
                'type': 'geojson',
                'data': linejson
            });

            // Add Layer
            map.addLayer({
              'id': 'marker-pasien',
              'type': 'symbol',
              'source': 'marker-pasien',
              'layout': {
              'icon-image': '{{ URL::asset("assets/img/icons/Patient.png") }}'
              }
            });

            // Map Click
            // Add styles to the map
            map.addLayer({
                id: 'measure-points',
                type: 'circle',
                source: 'linejson',
                paint: {
                    'circle-radius': 5,
                    'circle-color': '#000'
                },
                filter: ['in', '$type', 'Point']
            });

            map.addLayer({
                id: 'measure-lines',
                type: 'line',
                source: 'linejson',
                layout: {
                    'line-cap': 'round',
                    'line-join': 'round'
                },
                paint: {
                    'line-color': '#000',
                    'line-width': 2.5
                },
                filter: ['in', '$type', 'LineString']
            });

        });

      
        // Map On.click
        map.on('click', function(e) {
            var features = map.queryRenderedFeatures(e.point, {
                layers: ['measure-points']
            });

            // Remove the linestring from the group
            // So we can redraw it based on the points collection
            if (linejson.features.length > 1) linejson.features.pop();

            // Clear the Distance container to populate it with a new value
            distanceContainer.innerHTML = '';

            // If a feature was clicked, remove it from the map
            if (features.length) {
                var id = features[0].properties.id;
                linejson.features = linejson.features.filter(function(point) {
                    return point.properties.id !== id;
                });
            } else {
                var point = {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [e.lngLat.lng, e.lngLat.lat]
                    },
                    'properties': {
                        'id': String(new Date().getTime())
                    }
                };

                linejson.features.push(point);
            }

            if (linejson.features.length > 1) {
                linestring.geometry.coordinates = linejson.features.map(function(
                    point
                ) {
                    return point.geometry.coordinates;
                });

                linejson.features.push(linestring);

                // Populate the distanceContainer with total distance
                var value = document.createElement('pre');
                value.textContent =
                    'Jarak: ' +
                    turf.length(linestring).toLocaleString() +
                    'km';
                distanceContainer.appendChild(value);
            }

            map.getSource('linejson').setData(linejson);
        });

        // Button Configuration
        $(function(){
          $('#delete').attr('disabled', true);
        })

        $('#setRadius').on('click', function(){
          // Radius
          var radius = $('#radiusText').val();

          // Radius Coordinates
          map.addLayer({
              'id': 'pasien',
              'type': 'circle',
              'source': 'rumah-pasien',
              'paint': {
                'circle-radius': {
                  stops: [
                    [0, 0],
                    [20, metersToPixelsAtMaxZoom(radius, -6.9083514)]
                  ],
                  base: 2
                },
                'circle-opacity': 0.7,
                'circle-stroke-color': '#A01000',
                'circle-stroke-opacity': 0.5,
                'circle-color': '#0000FF',
              },
              'filter': ['==', '$type', 'Point']
          });

          // Disabled Input
          $('#setRadius').attr('disabled', true);
          $('#radiusText').attr('readonly', true);
          $('#delete').attr('disabled', false);

        });

        $('#delete').on('click', function(){
          // Delete Layer
          map.removeLayer('pasien');

          // Disabled button
          $('#delete').attr('disabled', true);
          $('#setRadius').attr('disabled', false);
          $('#radiusText').attr('readonly', false);

        });

        // Button Route
        $('#tracklog').on('click', function(){

           // Route
          map.addLayer({
              'id': 'route',
              'type': 'line',
              'source': 'TrackLog',
              'layout': {
                  'line-join': 'round',
                  'line-cap': 'round'
                  },
              'paint': {
                  'line-color': '#EF362E',
                  'line-width': 8
              }
          });

          // Animate
          startTime = performance.now();
          animateLine();

          // Zoom in
          // Geographic coordinates of the LineString
          var coordinates = geojson.features[0].geometry.coordinates;
          
          // Proses
          var bounds = coordinates.reduce(function(bounds, coord) {
            return bounds.extend(coord);
            }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
              
          map.fitBounds(bounds, {
            padding: 20
          });

        });
        
        // Pattient Location
        window.setInterval(function(){
          // Ajax
          $.ajax({
              url : "{{ url('/lokasi') }}",
              type : "GET",
              dataType : "JSON",
              success : function(data)
              {
                
                // New Marker
                var markerPatient = {
                  type: 'FeatureCollection',
                  features: 
                    {
                      // Patient
                      type: 'Feature',
                      geometry: {
                        type: 'Point',
                        coordinates: [data.longitude, data.latitude]
                      },
                      properties: {
                        title: 'Pasien',
                        description: 'Pasien'
                      }
                    }
                }

                // Set ulang LongLat
                patient.setLngLat(markerPatient.features.geometry.coordinates).addTo(map);
              }
          });
        }, 100);

        // add markers to map
        actor.features.forEach(function(marker) {

          // create a HTML element for each feature
          var el = document.createElement('div');
          if(marker.properties.title == 'Pasien')
            el.className = 'marker-patient';
          else
            el.className = 'marker-nurse';

          // make a marker for each feature and add to the map
          var markerActors = new mapboxgl.Marker(el)
                              .setLngLat(marker.geometry.coordinates)                          
                              .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
                              .setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'))
                              .addTo(map);
        });

       

        // var marker = new mapboxgl.Marker().setLngLat([107.584500, -6.907546]).addTo(map);

        // Only Number
        $('.only-number').keypress(function(event) {
          if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
              event.preventDefault();
          }
        });

        // Function 
        function animateLine(timestamp) 
        { 
          // restart if it finishes a loop
          if (progress > speedFactor * 360) 
          {
            startTime = timestamp;
            geojson.features[0].geometry.coordinates = [];
          } else {
            var x = progress / speedFactor;
            var y = Math.sin((x * Math.PI) / 90) * 40;
            // geojson.features[0].geometry.coordinates.push([x, y]);
            map.getSource('TrackLog').setData(geojson);
          }
          // Request the next frame of the animation.
          animation = requestAnimationFrame(animateLine);
        }

    </script>
@stop 
