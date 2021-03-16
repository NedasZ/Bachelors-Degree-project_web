<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
//use App\Http\Requests\EventRequest; ??
use Illuminate\Support\Facades\Hash; // ??

class EventController extends Controller
{
    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\View\View
     */
    public function setup()
    {
        return view('event.setup');
    }

    /**
     * Publish the event.
     *
     * ???
     */
    public function publish()
    {
        return;
    }
}
