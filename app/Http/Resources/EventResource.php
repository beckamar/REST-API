<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     * Retorna únicamente 5 propiedades
     * 
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            /**
             *se está utilizando la relación whenLoaded para determinar 
             * si la relación user está cargada antes de incluir el recurso UserResource.
             */
            'user' => new UserResource($this->whenLoaded('user')),

            // Recurso Attendee cuando la relación 'attendees' está cargada
            'attendees' =>  AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )
        ];
    }
}
