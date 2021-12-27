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
            'is_day_off' => $this->is_day_off,
            'is_trip' => $this->is_trip,
            'comment' => $this->comment,
            'remains_post' => $this->remains_post
        ];
    }
}
