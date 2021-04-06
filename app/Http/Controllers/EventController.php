<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Map;
use App\Models\Gps_location;
//use App\Http\Requests\EventRequest; ??
use Illuminate\Support\Facades\Hash; // ??
use Illuminate\Foundation\Http\FormRequest; //??
use Illuminate\Http\Request;



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
     * Show the form for creating an event.
     *
     * @return \Illuminate\View\View
     */
    public function list_all()
    {
        $events = Event::all();

        return view('event.list_all', ['events' => $events]);
    }

    /**
     * Show the form for creating an event.
     *
     * @return \Illuminate\View\View
     */
    public function list_user()
    {
        return view('event.list_user');
    }

    /**
     * Show the form for creating an event.
     * 
     * 
     * @param  int  $event_id
     * @return \Illuminate\View\View
     */
    public function event_info($event_id)
    {
        $event = Event::findOrFail($event_id);
    
        $role = 5;

        if(auth()->check()){

            $user_events = auth()->user()->events->where('id', $event->id);

            foreach($user_events as $user_event)
            {
                if($user_event->pivot->role <= 2)
                {
                    $role = $user_event->pivot->role;
                }
            }
        }


        return view('event.info', ['event' => $event, 'user_role' => $role, 'map' => $event->map]);
    }


    /**
     * Show the form for creating an event.
     * 
     * 
     * @param  int  $event_id
     * @return \Illuminate\View\View
     */
    public function event_edit($event_id)
    {
        $event = Event::findOrFail($event_id);
        
 
        return view('event.edit', ['event' => $event, 'map' => $event->map]);
    }

    /**
     * Publish the event.
     *
     * ???
     */
    public function event_update(Request $request, $event_id)
    {

        $event = Event::findOrFail($event_id);

        $event->name = $request->name;
        $event->description = $request->description;

        $event->save();
        
        return back()->withStatus(__('Event '.$event->name.' updated!'));
    }

    /**
     * Publish the event.
     *
     * ???
     */
    public function publish(Request $request)
    {
    
        $user_id = auth()->user()->id;
        $event_name = $request->name;

        $event = new Event;

        $event->name = $request->name;

        
        $event->save();
        $event->users()->attach($user_id, ['role' => '1']);
        
        return back()->withStatus(__('Event '.$event->name.' created!'));
    }

    public function map_upload(Request $request)
    {
        
        $event = Event::findOrFail($request->event_id);

        $map = new Map();
        $map->name = $request->name;
        $url = cloudinary()->upload($request->file('map_file')->getRealPath())->getSecurePath();
        
        $map->url = $url;
        if(!empty($request->description))
        {
            $map->description = $request->description;
        }
        $map->save();
        $event->map_id = $map->id;
        $event->save();
        
        
        return back()->withStatus(__('Event '.$event->name.' map attached!'));
        
    }

    /**
     * Publish the event.
     *
     * ???
     */
    public function map_update(Request $request)
    {


        return back()->withStatus(__('Map updated!'));
    }
}
