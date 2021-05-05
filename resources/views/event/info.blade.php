@extends('layouts.app', ['title' => __('Event Creation')])

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ __('Event info page') }}</h1>
                <p class="text-white mt-0 mb-5">{{ __('In this page you can see the event info accessable to everyone') }}</p>
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
                            @if($user_role <= 2)
                            <a href="/event_list/event/{{$event->id}}/edit" class="badge badge-primary">Edit</a>
                            @endif
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3>{{$event->name}}</h3>
                        <p> {{$event->description}} </p>
                        @isset($map)
                            <div class="col-md-3">
                                <div id = "map", style="height: 700px;width: 700px;">
                                    <div style="padding-right: 20px; padding-top: 30px; pointer-events: auto" class="leaflet-right leaflet-top">
                                        <button  class="btn btn-primary btn-sm" id="Btn-update_map" onclick="updatemap()" >Fix Map</button>
                                    </div>
                                </div>
                                <style>
                                    .olclass {
                                        transform-origin: center center !important;
                                    }   
                                </style>
                                <script>
                                    

                                    //map
                                    /*
                                    L.tileLayer('https://api.maptiler.com/maps/bright/{z}/{x}/{y}.png?key=2rZzSd8qs4TLWUESyE3I',
                                    {
                                        attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
                                    }).addTo(map);
                                    */

                                    //map_image
                                    
                                    var string = "{{$map->map_display_info}}";
                                    var pos = string.replace(/&quot;/g,"\"");
                                    
                                    var map_display_info = JSON.parse(pos);
                                    //console.log(map_display_info);

                                    var height = parseFloat(map_display_info.height);
                                    var width = parseFloat(map_display_info.width);    
                                    var scale = parseFloat(map_display_info.scale);
                                    var rotation = parseFloat(map_display_info.rotation);
                                    var lat = parseFloat(map_display_info.latitude);
                                    var lng = parseFloat(map_display_info.longitude);
                            
                                    var map = L.map('map').setView([lat, lng], 16).setMinZoom(8);
                                    var imageBounds = [[(-height * scale * 0.001 / 111111) + lat, width*scale*0.001/ (111111 * Math.cos(lat * (Math.PI/180)) ) + lng], [lat,lng]];
                                    //var imageBounds = [[-width * 0.000003 + 54.900796, height*0.000003+23.900176], [54.900796, 23.900176]];
                                    image = L.imageOverlay('{{$map->url}}',imageBounds,{opacity:0.8}).addTo(map);
                                    image.getElement().classList.add('olclass');
                                    image.getElement().style.transform = image.getElement().style.transform.replace(/ rotate\(.+\)/, "");
                                    image.getElement().style.transform += " rotate("+rotation+"deg)";


                                    //image and position
                                    function updatemap()
                                    {
                                        image.setBounds([[ lat + (-height * scale * 0.001 / 111111), lng + width*scale*0.001/(111111 * Math.cos(lat * (Math.PI/180)) )], [lat, lng]]);
                                        image.getElement().style.transform = image.getElement().style.transform.replace(/ rotate\(.+\)/, "");
                                        image.getElement().style.transform += " rotate("+rotation+"deg)";
                                    }
                                </script>       
                                <!--
                                    <img src="{{$map->url}}" alt="{{$map->name}}" width="600">
                                  --> 
                            </div>
                        @else
                        <div class="col-md-3">
                        <p> no map </p>
                            </div>
                        @endisset
                        <p> participants here </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
