<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Event;
use App\Models\PlatformUser;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Config;

class EventPolicy
{
    public function viewAny(?PlatformUser $platformUser): bool
    {
        return true;
    }

    public function view(?PlatformUser $platformUser, Event $event): bool
    {
        return true;
    }

    public function create(PlatformUser $platformUser): bool
    {
        // $user = PlatformUser::find($platformUser->id);
        // return $user->role === Config::get('variables.ONE') ||
        //     $user->role === Config::get('variables.TWO');

          return $platformUser->role === Config::get('variables.ONE') ||
            $platformUser->role === Config::get('variables.TWO');
    }

    public function delete(PlatformUser $platformUser, Event $event): bool
    {
        // $user = PlatformUser::find($platformUser->id);
        // $bookingData = Booking::where('event_id', $event->id)->first();
        // return $user->id === $bookingData->platform_user_id ||
        //     $user->role === Config::get('variables.TWENTY_THREE');
        return $platformUser->id === $event->booking->platform_user_id ||
            $platformUser->role === Config::get('variables.TWENTY_THREE');
    }
}
