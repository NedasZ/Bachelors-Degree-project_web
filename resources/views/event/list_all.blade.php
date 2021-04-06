@extends('layouts.app', ['title' => __('Event List')])

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ __('Event list page') }}</h1>
                <p class="text-white mt-0 mb-5">{{ __('In this page you see ALL the events.') }}</p>
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
                            <h3 class="mb-0">{{ __('All existing events') }}</h3>
                        </div>
                    </div>

                    <!-- Stuff goes here, including list >:( -->

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Id</th>
                                <th>Name</th>
                                <th>Map</th>
                                <th class="text-right">Created_at</th>
                                <th class="text-right">Updated_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td class="text-center">{{$event->id}}</td>
                                    <td>
                                        <a href="/event_list/event/{{$event->id}}">{{$event->name}} </a>
                                    </td>
                                    <td>{{$event->map_id}}</td>
                                    <td class="text-right">{{$event->created_at}}</td>
                                    <td class="text-right">{{$event->updated_at}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    
                    <!-- Stuff goes here, including list >:( -->

                </div>
            </div>
        </div>
    </div>
@endsection
