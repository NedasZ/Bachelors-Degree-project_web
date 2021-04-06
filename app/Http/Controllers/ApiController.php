<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Map;
use App\Models\Gps_location;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Resources\EventUserResource;


class ApiController extends Controller
{
    public $successStatus = 200;

    public function registerUser(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 401);            
                }

        $input = $request->all(); 
        $input['password'] = Hash::make($request->get('password'));
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function loginUser(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }


    public function joinEvent($eventName, Request $request){
        
        $event = Event::where('name','=',$eventName)->firstOrFail();
        $user = Auth::user();

        if(!$user->events->contains('id', $event->id))
        {
            $event->users()->attach($user->id, ['role' => '4']);
            return response()->json(['eventData' => $event, 'message' => 'joined first'], $this-> successStatus);
        }
        else
        {
            return response()->json(['eventData' => $event, 'message' => 'already joined'], $this-> successStatus);
        }
    }

    public function getEventData($eventId){
        $event = Event::findOrFail($eventId);
        $users = EventUserResource::collection($event->users()->get());

        return response()->json(['eventData' => $event, 'users' => $users], $this-> successStatus);
    }

    public function editEventUsers($eventId, Request $request){
        
        $validator = Validator::make($request->all(), [ 
            'users.*.id' => 'required|integer', 
            'users.*.role' => 'required|integer|min:2|max:4', 
        ]);
        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 403);            
                }

        $event = Event::findOrFail($eventId);
        
        foreach($request->users as $userToEdit)
        {
            $dbUsers = $event->users()->where('user_id', $userToEdit['id'])->get();
            foreach($dbUsers as $dbUser)
            {
                //return response()->json($dbUser->name, $this-> successStatus);
                if($dbUser->pivot->role == 1)
                {
                    return response()->json(['message' => 'Can\'t update event creator.'],403);
                }
                $event->users()->updateExistingPivot($userToEdit['id'], ['role'=>$userToEdit['role']]);

                }
        }
        return response()->json(['message' => 'Updated'], $this-> successStatus);
    }
    

    public function getUserEvents($userId){
        $user = User::findOrFail($userId);

        $events = $user->events()->get();

        return response()->json(['eventData' => $events], $this-> successStatus);
    }

    public function SaveResults(Request $request){
        $event = Event::findOrFail($request->eventId);
        
        $users = $event->users()->where('user_id', $request->userId)->get();

        $event->users()->updateExistingPivot($request->userId,['result'=>$request->result]);
        
        return response()->json([
            'message' => 'result updated',
            'result' => $request->result
        ], $this-> successStatus);
    }

    public function SaveUserLocation(Request $request){
        $validator = Validator::make($request->all(), [ 
            'event_id' => 'required|integer', 
            'user_id' => 'required|integer', 
            'locations' => 'required', 
        ]);
        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 403);            
                }

        $input = $request->all(); 
        $gps_locations = Gps_location::create($input); 
        
        return response()->json(['message'=>'upload succeeded', 'data'=>$gps_locations], $this-> successStatus); 

    }
    
    public function endEvent($eventId){
        $event = Event::findOrFail($eventId);
        $users = $event->users()->where('user_id', Auth::user()->id)->get();

        
        foreach($users as $user)
        {
            if($user->pivot->role == 1){
                return response()->json(['message'=>'event has ended'], $this-> successStatus); 
            }
            else{
                return response()->json(['message'=>'User cant end the event'], 403); 
            }
        }
    }

}
