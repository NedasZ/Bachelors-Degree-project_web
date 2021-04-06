@extends('layouts.app', ['title' => __('Event List')])

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ __('User Event list page') }}</h1>
                <p class="text-white mt-0 mb-5">{{ __('In this page you see the events related to you.') }}</p>
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
                            <h3 class="mb-0">{{ __('Your events') }}</h3>
                        </div>
                    </div>
                    

                    <!-- Stuff goes here, including list >:( -->


                </div>
            </div>
        </div>
    </div>
@endsection
