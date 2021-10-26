<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EventUpdateRequest;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Models\Event;

class PostponeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        $old_date_carbon = Carbon::create($event->date);
        if ($request->type == 'monthly') {
            $new_date_carbon = $old_date_carbon->addMonth();
        } elseif ($request->type == 'annual') {
            $new_date_carbon = $old_date_carbon->addYear();
        }    
        $new_date = $new_date_carbon->toDateString();
        $event->date = $new_date;
        $event->title = $request->title;
        $event->type = $request->type;

        $event->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
