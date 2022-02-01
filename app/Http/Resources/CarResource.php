<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Day;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $carbon = Carbon::today();
        $pre_month = [];
        $current_month = [];
        $all_days = DayResource::collection($this->days);

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
                    "January" => "Январь",
                    "February " => "Февраль",
                    "March" => "Март",
                    "April" => "Апрель",
                    "May" => "Май",
                    "June" => "Июнь",
                    "July" => "Июль",
                    "August" => "Август",
                    "September" => "Сентябрь",
                    "October" => "Октябрь",
                    "November" => "Ноябрь",
                    "December" => "Декабрь"
                );

        return [
            'car' => [
                'id' => $this->id,
                'model' => $this->model,
                'car_number' => $this->car_number,
                'region' => $this->region,
                'fuel_tank' => $this->fuel_tank,
                'summer_norm' => $this->summer_norm,
                'winter_norm' => $this->winter_norm,
                'user_id' => $this->user_id,
            ],
            'days' => [
                'all_days' => $all_days,
                'pre_month' => $pre_month,
                'current_month' => $current_month,             
                'current_month_name' => $carbon->format('F'),
                //'current_month_name' => $_monthsList[$carbon->format('F')],
                'previous_month_name' => $carbon->subMonthsNoOverflow(1)->format('F'),
                //'previous_month_name' => $_monthsList[$carbon->subMonthsNoOverflow(1)->format('F')],
            ]
        ];
    }
}
