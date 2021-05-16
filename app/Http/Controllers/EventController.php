<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Map;
use App\Models\gps_location;
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

        $events = auth()->user()->events;
        
       
        return view('event.list_user', ['events' => $events]);
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

        $UserLocations = [];
        $raw_locations= gps_location::where('event_id',$event_id)->get();
        foreach($raw_locations as $rec){
            $location_data = json_decode($rec->locations);
            if(array_key_exists($rec->user_id, $UserLocations))
            {
                $array = $UserLocations[$rec->user_id];
                foreach($location_data as $data){
                    $data->location = json_decode($data->location);
                    $array[$data->id] = $data->location;
                }
                ksort($array);
                $UserLocations[$rec->user_id] = $array;
            }
            else{
                $array = [];
                foreach($location_data as $data){
                    $data->location = json_decode($data->location);
                    $array[$data->id] = $data->location;
                }
                ksort($array);
                $UserLocations[$rec->user_id] = $array;
            }
        }

        //return($UserLocations);
        return view('event.info', ['event' => $event, 'user_role' => $role, 'map' => $event->map, 'user_locations' => json_encode($UserLocations)]);
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

        $event = new Event;

        $event->name = $request->name;
        $event->status = 1;

        
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
        $map = Map::findOrFail($request->map_id);
        $map->name = $request->name;
        $map->description = $request->description;
        $position = array(
            "latitude"=>$request->map_lat,
            "longitude"=>$request->map_lng,
            "height"=>$request->map_hgt,
            "width"=>$request->map_wdt,
            "scale"=>$request->map_scale,
            "rotation"=>$request->map_rotation
        );
        
        $map->map_display_info = $position;
        $map->save();
            
        return $map;       
        return back()->withStatus(__('Map updated!'));
    }

    public function end_event(Request $request)
    {
        $event = new Event;
        $users = $event->users()->where('user_id', auth()->user()->id)->get();

        foreach($users as $user)
        {
            if($user->pivot->role == 1){
                $event->status = 0;
                $event->save();
                
                return back()->withStatus(__('Event '.$event->name.' has ended.'));
            }
            else{
                return back()->withStatus(__('You can not end the '.$event->name.' event.'));
            }
        }
    }
}