<?php

namespace App\Http\Controllers;

use App\Contracts\PlatformUserInterface;
use App\Http\Resources\RolePermissionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RejectPartnerController extends Controller
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
    public function store(Request $request)
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
    public function update()
    {
        $users = $this->platformUserInterface->findActive('active', Config::get('variables.TEN'));
        $data = request()->validate([
            'selectedID' => 'required'
        ]);
        $userActive = Config::get('variables.SEVEN');
        $selectedID = $data['selectedID'];
        $users->each(function ($user) use ($userActive, $selectedID) {
            if ($user->id == $selectedID) {
                return $this->platformUserInterface->update(
                    'PlatformUser',
                    ['active' => $userActive],
                    $user->id
                );
            }
        });

        return response()->json([
            'message' => 'Reject partner successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
