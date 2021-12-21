<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Day;
use App\Http\Resources\DayResource;
use App\Http\Requests\DayStoreRequest;
use App\Http\Requests\DayUpdateRequest;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carbon = Carbon::today();
        $all_days = DayResource::collection(Day::where('user_id', Auth::id())->orderBy('date', 'asc')->get());
        $pre_month = [];
        $current_month = [];
        foreach ($all_days as $day) {
            $carbon = new Carbon();
            $day_carbon = Carbon::createFromDate($day->date);
            $diff = $carbon->diffInMonths($day->date);

            if($day_carbon->isCurrentMonth()) {
                /*
                $extract_day = $day_carbon->format('d');
                $day->date = $extract_day;
                */
                $current_month[] = $day;

            } elseif ($day_carbon->isLastMonth()) {
                /*
                $extract_day = $day_carbon->format('d');
                $day->date = $extract_day;
                */
                $pre_month[] = $day;
            }
        }       
        $arrays = array(
            'current_month_name' => $carbon->format('F'),
            'previous_month_name' => $carbon->subMonth()->format('F'),
            'all_days' => $all_days, 
            'current_month' => $current_month,  
            'pre_month' => $pre_month
        );   

        return $arrays;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DayStoreRequest $request)
    {
        $day = Day::create($request->validated());

        return new DayResource($day);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Day $day)
    {
        return new DayResource($day);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DayUpdateRequest $request, Day $day)
    {
        $day->update($request->validated());

        return new DayResource($day);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Day $day)
    {
        $day->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
