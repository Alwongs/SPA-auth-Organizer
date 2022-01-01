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

class PrintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DayStoreRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carbon = Carbon::today();
        $all_days = DayResource::collection(Day::where('user_id', $id)->orderBy('date', 'asc')->get());
        $pre_month = [];
        $current_month = [];
        foreach ($all_days as $day) {
            $carbon = new Carbon();
            $day_carbon = Carbon::createFromDate($day->date);
            $diff = $carbon->diffInMonths($day->date);

            if($day_carbon->isCurrentMonth()) {
                $current_month[] = $day;

            } elseif ($day_carbon->isLastMonth()) {
                $pre_month[] = $day;
            }
        }       
        $arrays = array(
            'current_month_name' => $carbon->format('F'),
            'previous_month_name' => $carbon->subMonthsNoOverflow(1)->format('F'),
            'all_days' => $all_days, 
            'current_month' => $current_month,  
            'pre_month' => $pre_month,
            'id' => $id
        ); 

        return $arrays;
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Day $day)
    {

    }
}
