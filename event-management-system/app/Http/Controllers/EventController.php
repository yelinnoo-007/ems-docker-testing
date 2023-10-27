<?php

namespace App\Http\Controllers;

use App\Contracts\BookingInterface;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Contracts\EventInterface;
use App\Http\Resources\BookingResource;
use App\Http\Resources\EventResource;
use App\Models\Booking;
use App\Models\Qrgenerate;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Config;

class EventController extends Controller
{
    use ImageTrait;
    private $genre;
    public function __construct(
        private EventInterface $eventInterface,
        private BookingInterface $bookingInterface
    ) {
        $this->middleware('auth:sanctum');
        $this->genre = Config::get('variables.THREE');
        $this->authorizeResource(Event::class, 'event');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event = Event::all();
        return EventResource::collection($event);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $validatedData = $request->validated();
        unset($validatedData['upload_url']);
        unset($validatedData['venue_id']);
        $event = $this->eventInterface->store('Event', $validatedData);
        $request->hasFile('upload_url') ?
            $this->storeImage($request, $event->id, $this->genre, $this->eventInterface) : false;

        //booking store
        $booking_data = [];
        $booking_data['platform_user_id'] = auth()->user()->id;
        $booking_data['venue_id'] = $request->venue_id;
        $booking_data['event_id'] = $event->id;
        $this->bookingInterface->store('Booking', $booking_data);
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $eventData = Booking::with(['event', 'venue.venueViewImage'])->where('event_id', $event->id)->where('book_status', '<>', Config::get('variables.ONE'))->first();
        return $eventData ? new BookingResource($eventData) : response(status: 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(EventRequest $request, Event $event)
    {

        // $validatedData = $request->validated();
        // unset($validatedData['venue_id']);
        // $event = $this->eventInterface->findByID('Event', $event->id);
        // if (!$event) {
        //     return response()->json([
        //         'message' => 'Event not found'
        //     ], 401);
        // }
        // $event = $this->eventInterface->update('Event', $validatedData, $event->id);
        // // $event->update($validatedData);
        // return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
       
        return $event->forceDelete() ? response(status: 204) : response(status: 500);
    }
}
