<?php

namespace App\Http\Controllers;

use App\Contracts\VenueCommentInterface;
use App\Http\Requests\VenueCommentRequest;
use App\Http\Requests\VenueRequest;
use App\Http\Resources\VenueCommentResource;
use App\Models\Venue;
use App\Models\VenueComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueCommentController extends Controller
{
    public function __construct(private VenueCommentInterface $venueCommentInterface)
    {
        $this->middleware('auth:sanctum')->only(['index', 'update', 'destroy']);
        $this->authorizeResource(VenueComment::class, 'venue_comment');
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Venue $venue)
    {
        $venueComments = $venue->venueComments()->get();
        return VenueCommentResource::collection($venueComments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(VenueCommentRequest $request, Venue $venue, VenueComment $venueComment)
    {
        $validated_data = $request->validated();
        $validated_data['platform_user_id'] = Auth::user()->id;
        $validated_data['venue_id'] = $venue->id;
        $venue_comment = $this->venueCommentInterface->update('VenueComment', $validated_data, $venueComment->id);
        return new VenueCommentResource($venue_comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue, VenueComment $venueComment)
    {
        return $this->venueCommentInterface->delete('VenueComment', $venueComment->id) ? response(status: 204) : response(status: 500);
    }
}
