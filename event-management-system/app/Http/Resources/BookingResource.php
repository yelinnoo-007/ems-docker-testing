<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'booking_id' => $this->id,
            'venue_id' => $this->venue_id,
            'event_id' => $this->event_id,
            'event' => new EventResource($this->whenLoaded('event')),
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'venue_image' => ImageResource::collection($this->venue->venueViewImage)
        ];
        //return parent::toArray($request);
    }
    public function with($request)
    {
        return [
            'version' => '1.0.0',
            'api_url' => url('http://127.0.0.1:8000/api/booking'),
            'message' => 'Your booking action is successfull'
        ];
    }
}
