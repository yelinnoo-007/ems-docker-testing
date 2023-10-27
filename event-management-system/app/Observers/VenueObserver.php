<?php

namespace App\Observers;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class VenueObserver
{
    public function deleting(Venue $venue)
    {
        $book_status = $venue->checkBookStatus($venue->id);
        //dd($book_status);
        if ($book_status) {
            return false;
        }

        $venue->venueImage()->each(function ($image)  use ($venue) { //deleting venue images
            $image->where('link_id', $venue->id)->where('genre', Config::get('variables.TWO'))->delete();
        });
        $venue->booking()->each(function ($book) { //deleting event images
            $book->event->eventImage()->where('genre', Config::get('variables.THREE'))->delete();
            $book->event->adHoc()->each(function ($adhoc) {
                $adhoc->qrTicket()->delete();
                $adhoc->delete();
            });
            $book->event->delete();
            $book->delete();
        });
        $venue->venueRatings()->delete();
        $venue->venueComments()->delete();
    }

    public function restoring(Venue $venue): void
    {
        //$venue->images()->restore();
        $venue->venueImage()->withTrashed()->each(function ($image) use ($venue) {
            $image->where('link_id', $venue->id)->where('genre', Config::get('variables.TWO'))->restore();
        });
        $venue->booking()->withTrashed()->each(function ($book) {
            $book->restore();
            $book->event()->withTrashed()->restore();
            $book->event->eventImage()->withTrashed()->where('genre', Config::get('variables.THREE'))->restore();

            $book->event->adHoc()->withTrashed()->each(function ($adhoc) {
                $adhoc->withTrashed()->restore();
                $adhoc->qrTicket()->withTrashed()->restore();
            });
        });

        $venue->venueRatings()->restore();
        $venue->venueComments()->restore();
    }
}
