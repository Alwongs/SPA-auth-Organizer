<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DayResource;
use App\Models\Day;
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
        $carbon = Carbon::today();
        $all_days = DayResource::collection(Day::where('user_id', 4)->orderBy('date', 'asc')->get());
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

        return view('print', [
            'current_month_name' => $carbon->format('F'),
            'previous_month_name' => $carbon->subMonth()->format('F'),
            'all_days' => $all_days, 
            'current_month' => $current_month,  
            'pre_month' => $pre_month
        ]);
    }
}
