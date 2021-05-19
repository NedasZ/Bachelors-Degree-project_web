@extends('layouts.app', ['title' => __('Event Creation')])

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ __('Event edit page') }}</h1>
                <p class="text-white mt-0 mb-5">{{ __('In this page you can edit the event info') }}</p>
            </div>
        </div>
    </div>
</div> 
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">
                            {{ __('Event Info!') }}
                             <a href="/event_list/event/{{$event->id}}" class="badge badge-primary">Back</a>
                            </h3>

                        </div>
                    </div>
                    <div class="card-body">

                        <p> Only admins can be here </p>
                        <div class="col-md-6">
                            <form method="post" action="{{ route('event_update', ['event_id' => $event->id]) }}" autocomplete="off">
                                @csrf
                                @method('put')

                                <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                                
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('status') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif


                                <div class="pl-lg-0">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $event->name) }}" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                        <textarea rows="3" type="text" name="description" id="input-description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}">{{ old('description', $event->description) }}</textarea >

                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('route') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-route">{{ __('Route') }}</label>
                                        <p >{{ __('Event track point numbers need to be seperated by "-"') }}</p>
                                        <p >{{ __('ex: 31-32-32-34-100') }}</p>
                                        <input type="text" name="route" id="input-Route" class="form-control form-control-alternative{{ $errors->has('route') ? ' is-invalid' : '' }}" placeholder="{{ __('00-00-00-00') }}"></input >
                                    </div>

                                    <div class="form-group{{ $errors->has('si_eid') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-si_eid">{{ __('Si Center event id') }}</label>
                                        <input type="text" name="si_eid" id="input-si_eid" class="form-control form-control-alternative{{ $errors->has('si_eid') ? ' is-invalid' : '' }}" placeholder="{{ __('1234') }}" value="{{ isset($results) ? $results->si_event_id : null }}"></input >
                                    </div>

                                    <div class="form-group{{ $errors->has('si_api_key') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-si_api_key">{{ __('Si Center api key') }}</label>
                                        <input rows="2" type="text" name="si_api_key" id="input-si_api_key" class="form-control form-control-alternative{{ $errors->has('si_api_key') ? ' is-invalid' : '' }}" placeholder="{{ __('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa') }}" value="{{ isset($results) ? $results->si_api_key : null }}"></input >
                                    </div>
                                    <div class="text-left">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <br>
                        </div>
                        @isset($map)
                            <div class="container">
                                <div class="row">
                                    <div class="col-8">
                                        <div id = "map", style="height: 700px;width: 100%;">
                                            <div style="padding-right: 20px; padding-top: 30px; pointer-events: auto" class="leaflet-right leaflet-top">
                                                <button  class="btn btn-primary btn-sm" id="Btn-focus" onclick="bringmarkertocenter()" >Bring Marker</button>
                                                <button  class="btn btn-primary btn-sm" id="Btn-focus" onclick="mapfocusesmarker()" >Focus Marker</button>
                                                <button  class="btn btn-primary btn-sm" id="Btn-update_map" onclick="updatemap()" >Update Map</button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-4">
                                        <form method="post" action="{{ route('map_update') }}" autocomplete="off">
                                            @csrf
                                            @method('post')

                                            <h6 class="heading-small text-muted mb-4">{{ __('Map information') }}</h6>
                                            
                                            @if (session('status'))
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    {{ session('status') }}
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif


                                            <div class="pl-lg-4">
                                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Map_name') }}" value="{{ old('name', $map->name) }}" required>

                                                    @if ($errors->has('name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                                    <textarea type="text" rows="3" name="description" id="input-description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{$map->description}}" required></textarea>

                                                    @if ($errors->has('description'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('description') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>      
                                                <div style="text-align: right;">
                                                    <label class="form-control-label" for="map_lat">{{ __('Latitude') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_lat" id="map_lat" class="form-control" placeholder="{{ __('000') }}" required>

                                                    <label class="form-control-label" for="input-map_lng">{{ __('Logitude') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_lng" id="map_lng" class="form-control" placeholder="{{ __('000') }}" required>

                                                    <label class="form-control-label" for="input-map_hgt">{{ __('Height (mm)') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_hgt" id="map_hgt" class="form-control" placeholder="{{ __('000') }}" required>

                                                    <label class="form-control-label" for="map_wdt">{{ __('Width (mm)') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_wdt" id="map_wdt" class="form-control" placeholder="{{ __('000') }}" required>

                                                    <label class="form-control-label" for="map_scale">{{ __('Scale (1:X)') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_scale" id="map_scale" class="form-control" placeholder="{{ __('000') }}" required>

                                                    <label class="form-control-label" for="map_rotation">{{ __('Rotation') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_rotation" id="map_rotation" class="form-control" placeholder="{{ __('000') }}" required>

                                                    <label class="form-control-label" for="map_opacity">{{ __('Opacity') }}</label>
                                                    <input style="text-align: right;" type="text" name="map_opacity" id="map_opacity" class="form-control" placeholder="{{ __('000') }}" required>
                                                    <input type="hidden" id="map_id" name="map_id" value="{{$map->id}}">
                                                </div>                                          
                                                <div class="text-left">
                                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                 </div>
                                 <style>
                                    .olclass {
                                        transform-origin: center center !important;
                                    }   
                                 </style>
                                 <script>

                                    var string = "{{$map->map_display_info}}";
                                    var pos = string.replace(/&quot;/g,"\"");                                            
                                    var map_display_info = JSON.parse(pos);
                                    var lat_start = parseFloat(map_display_info.latitude);
                                    var lng_start = parseFloat(map_display_info.longitude);

                                    function bringmarkertocenter(){
                                        moveMarker(marker,map.getCenter());
                                    }
                                    function mapfocusesmarker(){
                                        map.setView(marker.getLatLng());
                                    }

                                    var map = L.map('map').setView([lat_start, lng_start], 16).setMinZoom(8);

                                    //map
                                    L.tileLayer('https://api.maptiler.com/maps/bright/{z}/{x}/{y}.png?key=2rZzSd8qs4TLWUESyE3I',
                                    {
                                        attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
                                    }).addTo(map);

                                    //marker
                                    var marker = new L.marker([lat_start, lng_start], {draggable:'true'});
                                    
                                    
                                    //console.log(map_display_info);

                                    
                                   
                                    
                                    
                                    
                                    

                                    map.addLayer(marker);
                                    var latlng = [marker.getLatLng()];
                                    var polyline = L.polyline(latlng, {color: 'red'}).addTo(map);
                                    
                                    //map parameters
                                    var width = 210; 
                                    width = parseFloat(map_display_info.width);    
                                    var element_wdt = document.getElementById("map_wdt");
                                    element_wdt.value = width;

                                    var height = 297;
                                    height = parseFloat(map_display_info.height);
                                    var element_hgt = document.getElementById("map_hgt");
                                    element_hgt.value = height;

                                    var scale = 10000;
                                    scale = parseFloat(map_display_info.scale);
                                    var element_scale = document.getElementById("map_scale");
                                    element_scale.value = scale;

                                    var rotation = 0;
                                    rotation = parseFloat(map_display_info.rotation);
                                    var element_rotation = document.getElementById("map_rotation");
                                    element_rotation.value = rotation;

                                    var m_opacity = 0.5;
                                    var element_opacity = document.getElementById("map_opacity");
                                    element_opacity.value = m_opacity;

                                    var element_lat = document.getElementById("map_lat");
                                    element_lat.value =marker.getLatLng().lat;

                                    var element_lng = document.getElementById("map_lng");
                                    element_lng.value =marker.getLatLng().lng;
                                    
                                    var imageBounds = [[(-height * scale * 0.001 / 111111) + marker.getLatLng().lat, width*scale*0.001/ (111111 * Math.cos(marker.getLatLng().lat * (Math.PI/180)) ) + marker.getLatLng().lng], marker.getLatLng()];
                                    image = L.imageOverlay('{{$map->url}}',imageBounds,{opacity: m_opacity}).addTo(map);
                                    image.getElement().classList.add('olclass');
                                    
                                    
                                    marker.on('dragend', function(event){
                                            var marker = event.target;
                                            var position = marker.getLatLng();
                                            moveMarker(marker, position);
                                        });
                                    
                                    function moveMarker(mark, pos)
                                    {
                                        mark.setLatLng(new L.LatLng(pos.lat, pos.lng),{draggable:'true'});
                                        image.setBounds( [[ pos.lat + (-height * scale * 0.001 / 111111), pos.lng +  width*scale*0.001/ (111111 *Math.cos(pos.lat * (Math.PI/180)))], [pos.lat, pos.lng]]);
                                        
                                        image.getElement().style.transform = image.getElement().style.transform.replace(/ rotate\(.+\)/, "");
                                        image.getElement().style.transform += " rotate("+rotation+"deg)";

                                        element_lat.value = pos.lat;

                                        
                                        element_lng.value = pos.lng;
                                        
                                        if(latlng.length > 5){
                                            latlng.shift();
                                        }
                                        latlng.push([pos.lat, pos.lng]);
                                        
                                        polyline.setLatLngs(latlng);
                                    }

                                    function updatemap()
                                    {
                                        
                                        height = element_hgt.value;
                                        width = element_wdt.value;
                                        scale = element_scale.value;
                                        m_opacity = element_opacity.value;
                                        rotation = element_rotation.value;

                                        if(element_lat.value != marker.getLatLng().lat || element_lng.value != marker.getLatLng().lng){
                                            marker.setLatLng(new L.LatLng(element_lat.value, element_lng.value),{draggable:'true'});
                                            if(latlng.length > 5){
                                                latlng.shift();
                                            }
                                            latlng.push([element_lat.value, element_lng.value]);
                                            polyline.setLatLngs(latlng);
                                        }

                                        

                                        image.setOpacity(m_opacity);
                                        image.setBounds([[ marker.getLatLng().lat + (-height * scale * 0.001 / 111111), marker.getLatLng().lng + width*scale*0.001/(111111 * Math.cos(marker.getLatLng().lat * (Math.PI/180)) )], [marker.getLatLng().lat, marker.getLatLng().lng]]);
                                        image.getElement().style.transform = image.getElement().style.transform.replace(/ rotate\(.+\)/, "");
                                        image.getElement().style.transform += " rotate("+rotation+"deg)";
                                    }
                                    //image and position
                                </script>
                            </div>
                        @else
                            <p> map upload 
                            
                            <form method="post" action="{{ route('upload_map') }}" enctype="multipart/form-data">

                            @csrf


                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <p>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="" required autofocus>
                                    </p>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                    <p>
                                    <input type="file" name="map_file" id="map_file"/>
                                    </p>
                                    <input type="hidden" id="event_id" name="event_id" value="{{$event->id}}">
                                </div>
                                <div class="text-left">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Upload') }}</button>
                                </div>
                                
                            </div>
                        </form>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
