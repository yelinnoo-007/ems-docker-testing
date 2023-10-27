<?php

namespace App\Http\Controllers;

use App\Contracts\PlatformUserInterface;
use App\Http\Resources\RolePermissionResource;
use App\Models\PlatformUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UpdatePartnerController extends Controller
{
    private $platformUserInterface;
    public function __construct(PlatformUserInterface $platformUserInterface)
    {
        $this->platformUserInterface = $platformUserInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pending_users = $this->platformUserInterface->findActive('active', Config::get('variables.TEN'));
        return RolePermissionResource::collection($pending_users);
    }



    public function show(string $id)
    {
        //
    }


    public function update()
    {
        $users = $this->platformUserInterface->findActive('active', Config::get('variables.TEN'));
        $data = request()->validate([
            'selectedID' => 'required'
        ]);
        $userActive = Config::get('variables.SEVEN');
        $userRole = Config::get('variables.THREE');
        $selectedID = $data['selectedID'];
        $users->each(function ($user) use ($userActive, $userRole, $selectedID) {
            if ($user->id == $selectedID) {
                return $this->platformUserInterface->update(
                    'PlatformUser',
                    ['active' => $userActive, 'role' => $userRole],
                    $user->id
                );
            }
        });

        return response()->json([
            'message' => 'Approved partner successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
