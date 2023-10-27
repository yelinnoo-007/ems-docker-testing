<?php

namespace App\Policies;

use App\Http\Requests\AdHocRequest;
use App\Models\AdHoc;
use App\Models\PlatformUser;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Config;

class AdHocPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(PlatformUser $platformUser): bool
    {
        // $user = PlatformUser::find($platformUser->id);
        // return $user->role === Config::get('variables.FOUR') ||
        //         $user->role === Config::get('variables.TWENTY_THREE');

        return $platformUser->role === Config::get('variables.FOUR') ||
            $platformUser->role === Config::get('variables.TWENTY_THREE');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(PlatformUser $platformUser, AdHoc $adHoc): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(PlatformUser $platformUser): bool
    {
        // $user = PlatformUser::find($platformUser->id);
        // return $user->role === Config::get('variables.FOUR') ||
        //         $user->role === Config::get('variables.TWENTY_THREE');
        return $platformUser->role === Config::get('variables.FOUR') ||
            $platformUser->role === Config::get('variables.TWENTY_THREE');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(PlatformUser $platformUser, AdHoc $adhoc): bool
    {
        // $user = PlatformUser::find($platformUser->id);
        // return $user->role === Config::get('variables.FOUR') ||
        //         $user->role === Config::get('variables.TWENTY_THREE');
        return $platformUser->role === Config::get('variables.FOUR') ||
            $platformUser->role === Config::get('variables.TWENTY_THREE');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(PlatformUser $platformUser, AdHoc $adhoc): bool
    {
        // $user = PlatformUser::find($platformUser->id);
        // return $user->role === Config::get('variables.FOUR') ||
        //     $user->role === Config::get('variables.TWENTY_THREE');

        return $platformUser->role === Config::get('variables.FOUR') ||
            $platformUser->role === Config::get('variables.TWENTY_THREE');
    }
}
