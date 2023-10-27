<?php

namespace App\Http\Controllers;

use App\Contracts\PlatformUserInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\RolePermissionResource;
use App\Models\Common;
use App\Models\PlatformUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RequestPartnerController extends Controller
{
    private $platformUserInterface;
    public function __construct(PlatformUserInterface $platformUserInterface)
    {
        $this->middleware('auth:sanctum')->only(['update']);
        $this->platformUserInterface = $platformUserInterface;
    }


    public function update()
    {
        $user = PlatformUser::find(auth()->user()->id);
        $userActive = Config::get('variables.TEN');
        $this->platformUserInterface->update('PlatformUser', ['active' => $userActive], $user->id);
        return response()->json([
            'message' => 'Become partner request successfully'
        ]);
    }
}
