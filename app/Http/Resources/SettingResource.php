<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Day;

class SettingResource extends JsonResource
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
            'season' => $this->season,
            'default_car_id' => $this->default_car_id,
            'percent' => $this->percent,
            'user_id' => $this->user_id
        ];
    }
}
