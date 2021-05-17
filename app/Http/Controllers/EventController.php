<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Map;
use App\Models\gps_location;
//use App\Http\Requests\EventRequest; ??
use Illuminate\Support\Facades\Hash; // ??
use Illuminate\Support\Facades\Http;
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
        return view('event.info', ['event' => $event, 'user_role' => $role, 'map' => $event->map, 'user_locations' => json_encode($UserLocations), 'results' => json_decode($event->results)]);
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
        
        
        return view('event.edit', ['event' => $event, 'map' => $event->map, 'results' => json_decode($event->results)]);
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
        if($request->description != null){
            $event->description = $request->description;
        }
        

        if($request->route != null && $request->si_eid != null && $request->si_api_key != null){
            $route = explode("-",$request->route);
            //$result = json_decode("{\"route\": null,\"last_id\": null,\"results\": null}");
            $result = new \stdClass();
            $result->route = $route;
            $result->si_event_id = $request->si_eid;
            $result->si_api_key = $request->si_api_key;
            $result->last_id = 0;
            $result->results = [];
            $event->results = json_encode($result);
        }
        


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

    public function end_event($event_id)
    {
         
        $event = Event::findOrFail($event_id);
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

    public function event_get_results($event_id){

        $event = Event::findOrFail($event_id);
        
        if($event->results == null){
           
            return back()->withStatus(__($event->name.' isn\'t setup for result tracking.'));
        }

        $results = json_decode($event->results);
       
        $si_event_id = $results->si_event_id;
        //return $si_event_id;
        $last_id_string = '';
        if($results->last_id > 0){
            $last_id_string = '?afterId='+$results->last_result_id;
        }
        $url = 'https://center-origin.sportident.com/api/rest/v1/public/events/'.$si_event_id.'/punches'.$last_id_string;
        
        $response = HTTP::withHeaders([
            'apikey'=> $results->si_api_key
        ])->get($url);

        $response = $response->object();

        
        //Atkomentuti kai bus daugiau rezultatu
        //-------------------------------------------------------
        //$results->last_id = $response[count($response)-1]->id;


        $runner_array = [];
        
        for($i = 0; $i< count($response); $i++){
            if(!array_key_exists($response[$i]->card, $runner_array)){
                $o = [];
                $o[0] = $response[$i];
                $runner_array[$response[$i]->card] = $o;
            }
            else{
                array_push($runner_array[$response[$i]->card], $response[$i]);
                //reikia gal aray usort sort uzdeti, kad surikiuotu pagal laika
            }
        }


        foreach($runner_array as $si => $punch_array){
            
            $started = false;
            $res_array = [];
            $obj = new \stdClass;
            $start_index = 0;
            for($i = 0; $i < count($punch_array); $i++){
                $res = new \stdClass;
                if(!$started && $punch_array[$i]->mode == "Start"){
                    $started = true;
                    $start_index = $i;
                    $res->code = "Start";
                    $res->date = date('Y-m-d H:i:s', $punch_array[$i]->time / 1000);
                    $res->time_between = date('H:i:s', 0);
                    $res->time_total = date('H:i:s', 0);
                    $res_array[] = $res;
                }
                if($started){
                    if($punch_array[$i]->mode == "Control" || $punch_array[$i]->mode == "Finish"){
                        if($punch_array[$i]->mode == "Finish")
                        {
                            $res->code = "Finish";
                        }else{
                            $res->code = $punch_array[$i]->code;
                        }
                        
                        $res->date = date('Y-m-d H:i:s', $punch_array[$i]->time / 1000);
                        $res->time_between = date('H:i:s', ($punch_array[$i]->time - $punch_array[$i-1]->time) / 1000);
                        $res->time_total = date('H:i:s',  ($punch_array[$i]->time -  $punch_array[$start_index]->time) / 1000);
                        $res_array[] = $res;
                    }
                }
            }
            $obj->si_card = $si;
            $obj->punch_data = $punch_array;
            $obj->time_data = $res_array;

            $runner_array[$si] = $obj;
        }

        $results->results = $runner_array;
        $event->results = json_encode($results);
        $event->save();
        
        return back()->withStatus(__('Event '.$event->name.' results have been updated.'));
    }
}