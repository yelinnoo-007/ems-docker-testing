<?php

namespace App\Http\Controllers;

use App\Contracts\VenueCommentInterface;
use App\Contracts\VenueRatingInterface;
use App\Http\Requests\VenueRatingRequest;
use App\Http\Requests\VenueReviewRequest;
use App\Http\Resources\VenueRatingResource;
use App\Models\Venue;
use App\Models\VenueRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueRatingController extends Controller
{
    public function __construct(
        private VenueRatingInterface $venueRatingInterface,
        private VenueCommentInterface $venueCommentInterface
    ) {
        $this->middleware('auth:sanctum')->except(['create', 'edit']);
        $this->authorizeResource(VenueRating::class, 'venue_rating');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Venue $venue)
    {
        $venues_rating = $venue->venueRatings()->get();
        return VenueRatingResource::collection($venues_rating);
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
    public function store(VenueReviewRequest $request, Venue $venue)
    {
        $validated_data = $request->validated();
        $venueRtating = $this->venueRatingInterface->relationStore($venue, $request, $validated_data);
        //$venue_rating = $this->venueRatingInterface->relationStore('VenueRating', $venue, 'venue', $validated_data);
        //$this->venueCommentInterface->relationStore('VenueComment', $venue, 'venue', $validated_data);

        return new VenueRatingResource($venueRtating);
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue, VenueRating $venue_rating)
    {
        return $venue_rating;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VenueRatingRequest $request, Venue $venue, VenueRating $venueRating)
    {
        $validated_data = $request->validated();
        $validated_data['platform_user_id'] = Auth::user()->id;
        $validated_data['venue_id'] = $venue->id;
        $venue_rating = $this->venueRatingInterface->update('VenueRating', $validated_data, $venueRating->id);
        return new VenueRatingResource($venue_rating);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue, VenueRating $venueRating)
    {
        return $this->venueRatingInterface->delete('VenueRating', $venueRating->id) ? response(status: 204) : response(status: 500);
    }
}
