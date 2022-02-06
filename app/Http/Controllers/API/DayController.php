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
        $all_days = DayResource::collection(Day::where('car_id', 1)->orderBy('date', 'asc')->get());
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
        //список месяцев с названиями для замены
        $_monthsList = array(
            "January" => "январь",
            "February " => "февраль",
            "March" => "март",
            "April" => "апрель",
            "May" => "май",
            "June" => "июнь",
            "July" => "июль",
            "August" => "август",
            "September" => "сентябрь",
            "October" => "октябрь",
            "November" => "ноябрь",
            "December" => "декабрь"
        );

        $_monthsListModif = array(
            "January" => "января",
            "February " => "февраля",
            "March" => "марта",
            "April" => "апреля",
            "May" => "мая",
            "June" => "июня",
            "July" => "июля",
            "August" => "августа",
            "September" => "сентября",
            "October" => "октября",
            "November" => "ноября",
            "December" => "декабря"
        );      

        $arrays = array(
            'current_month_name' => $_monthsList[$carbon->format('F')],
            'previous_month_name' => $_monthsList[$carbon->subMonthsNoOverflow(1)->format('F')],
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
