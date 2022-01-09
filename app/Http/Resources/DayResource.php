<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'remains_pre' => $this->remains_pre,
            'odo_pre' => $this->odo_pre,
            'fuel' => $this->fuel,
            'odo_post' => $this->odo_post,
            'work_in_red' => $this->work_in_red,
            'is_trip' => $this->is_trip,
            'red_day' => $this->red_day,
            'comment' => $this->comment,
            'remains_post' => $this->remains_post,
            'user_id' => $this->user_id
        ];
    }
}
