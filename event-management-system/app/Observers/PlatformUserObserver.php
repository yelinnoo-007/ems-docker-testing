<?php

namespace App\Observers;

use App\Models\PlatformUser;
use Illuminate\Support\Facades\Config;

class PlatformUserObserver
{

    public function deleting(PlatformUser $platformUser)
    {
        $bookStatus = $platformUser->checkBookStatus($platformUser->id);
        //dd($bookStatus);
        if ($bookStatus) {
            return false;
        }

        $platformUser->profileImage()->each(function ($image) use ($platformUser) { //deleting platformUser images
            $image->where('link_id', $platformUser->id)->where('genre', Config::get('variables.ONE'))->delete();
        });

        $platformUser->address->each(function ($address) {
            $address->street->ward()->delete();
            $address->street()->delete();
            $address->delete();
        });

        $platformUser->venue()->each(function ($venue) {
            $venue->venueImage()->where('genre', Config::get('variables.TWO'))->delete();
            $venue->delete(); //this line says to Laravel. Hey, i am deleting in your venue model and venueObserver looks for that changes.
        });

        // $platformUser->booking()->each(function ($book) { //deleting event images
        //     $attendes = $book->event->adHoc()->get();
        //     //dd($attendes->count());
        //     $book->event->eventImage()->where('genre', Config::get('variables.THREE'))->delete();
        //     foreach ($attendes as $attende) {
        //         $attende->qrTicket()->delete();
        //         $attende->delete();
        //     }
        //     $book->event()->delete();
        //     $book->delete();
        // });
        // $platformUser->venueRating()->delete();
        // $platformUser->venueComment()->delete();
    }

    public function restoring(PlatformUser $platformUser)
    {
        $platformUser->profileImage()->withTrashed()->each(function ($image) { //deleting platformUser images
            $image->where('genre', Config::get('variables.ONE'))->restore();
        });

        $platformUser->address()->withTrashed()->each(function ($address) {
            $address->restore();
            $address->street()->withTrashed()->restore();
            $address->street->ward()->withTrashed()->restore();
        });

        $platformUser->venue()->withTrashed()->each(function ($venue) {
            $venue->restore(); //this line says to Laravel. Hey, i am restoring in your venue model and venueObserver looks for that changes.
            $venue->venueImage()->withTrashed()->where('genre', Config::get('variables.TWO'))->restore();
        });

        // $platformUser->booking()->withTrashed()->each(function ($book) { //deleting event images
        //     $book->restore();
        //     $book->event->withTrashed()->restore();
        //     $book->event->eventImage()->withTrashed()->where('genre', Config::get('variables.THREE'))->restore();

        //     $book->event->adHoc()->withTrashed()->each(function ($adhoc) {
        //         $adhoc->withTrashed()->restore();
        //         $adhoc->qrTicket()->withTrashed()->restore();
        //     });
        // });
        // $platformUser->venueRating()->restore();
        // $platformUser->venueComment()->restore();
    }
}
