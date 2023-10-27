<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait VenueTrait
{
  public function venueDetails(string $modelName, int $id)
  {
    $model = app("App\\Models\\{$modelName}");
    $venue = $model::with(['venueRatings', 'venueImage', 'venueComments', 'booking.event'])->withCount('venueRatings')->find($id);
    $averageRating = ($venue->venue_rating_count != 0) ?
      $venue->venueRatings->sum('rating_id') / $venue->venue_ratings_count : 0;
    $venue->average_rating = $averageRating;

    // $venues_images = VenueImage::where('link_id', $venue->id)->get(); old way
    // $venue->venue_image = $venues_images;
    $venueImages = [];
    foreach ($venue->venueImage as $img) {
      $image = [
        'venue_id' => $img['venue_id'],
        'upload_url' => $img['upload_url'],
      ];
      $venueImages[] = $image;
    }
    $venue->venue_images = $venueImages;

    $venuesWithEvents = [];
    foreach ($venue->booking as $book) {
      $event = [
        'venue_id' => $book['venue_id'],
        'event_name' => $book['event']['event_name'], //$booking->event->event_name;
      ];
      $venuesWithEvents[] = $event;
    }
    $venue->venue_events = $venuesWithEvents;

    unset($venue->venueRatings);
    unset($venue->venueImage);
    unset($venue->booking);
    return $venue;
  }

  public function venueRating($filteredVenues)
  {
    
    $filteredVenuesCount = is_countable($filteredVenues) ? count($filteredVenues) : 1;
    if ($filteredVenuesCount == 1) {
      $averageRating = ($filteredVenues->venue_ratings_count != 0) ?
        $filteredVenues->venueRatings->sum('rating_id') / $filteredVenues->venue_ratings_count : 0;
      $filteredVenues->average_rating = $averageRating;
      return $filteredVenues;
    }

    $final_venues = $filteredVenues->map(function ($venue) {
      $averageRating = ($venue->venue_ratings_count != 0) ?
        $venue->venueRatings->sum('rating_id') / $venue->venue_ratings_count : 0;
      $venue->average_rating = $averageRating;
      return $venue;
    });
    return $final_venues;
  }
}
