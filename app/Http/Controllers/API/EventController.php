<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carbon = Carbon::today();
        $events = EventResource::collection(Event::where('user_id', Auth::id())->orderBy('date', 'asc')->get());
        $overdue = [];
        $today = [];
        $tomorrow = [];
        $in_week = [];
        foreach ($events as $event) {
            $event_date_carbon = Carbon::createFromDate($event->date);
            $diff = $carbon->diffInDays($event->date);

            if($carbon->lt($event_date_carbon)) {
                if($diff > 1 && $diff <=7) {
                    $in_week[] = $event;
                } elseif ($diff == 1) {
                    $tomorrow[] = $event;
                }
            } elseif ($carbon->eq($event_date_carbon)) {
                $today[] = $event;
            } else {
                $overdue[] = $event;
            }
        }       
        $arrays = array(
            'events' => $events, 
            'overdue' => $overdue,  
            'today' => $today,
            'tomorrow' => $tomorrow, 
            'in_week' => $in_week
        );      
        return $arrays;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventStoreRequest $request)
    {
        $event = Event::create($request->validated());

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventUpdateRequest $request, Event $event)
    {        
        $event->update($request->validated()); 

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
