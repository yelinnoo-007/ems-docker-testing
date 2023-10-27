<?php

namespace App\Http\Controllers;

use App\Contracts\PlatformUserInterface;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\PlatformUserResource;
use App\Http\Resources\RolePermissionResource;
use App\Models\PlatformUser;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Type\Integer;

class RolePermissionController extends Controller
{
    private $platformUserInterface;
    private $individual, $corporate, $partner, $staff, $admin;
    private $individualType, $corporateType, $partnerType, $staffType, $adminType;

    public function __construct(PlatformUserInterface $platformUserInterface)
    {
        $this->platformUserInterface = $platformUserInterface;
        $this->individual = Config::get('variables.ONE');
        $this->corporate = Config::get('variables.TWO');
        $this->partner = Config::get('variables.THREE');
        $this->staff = Config::get('variables.FOUR');
        $this->admin = Config::get('variables.TWENTY_THREE');
        $this->individualType = Config::get('variables.INDIVIDUAL');
        $this->corporateType = Config::get('variables.CORPORATE');
        $this->adminType = Config::get('variables.SYSTEM_ADMIN');
        $this->partnerType = Config::get('variables.PARTNER');
        $this->staffType = Config::get('variables.STAFF');
    }

    public function index()
    {
        $allowedRoles = [$this->admin, $this->partner, $this->staff];
        $role_user = $this->platformUserInterface->findRole('role', $allowedRoles);
        return RolePermissionResource::collection($role_user);
    }

    public function store(AuthRequest $request)
    {
        $validatedData = $request->validated();
        if ($validatedData['role'] === $this->individualType || $validatedData['role'] === $this->corporateType) {
            return response()->json([
                'message' => 'This is the normal and corporate user cannot store'
            ]);
        }
        $role = strtolower($request->role);
        if ($role  === $this->partnerType) {
            $validatedData['role'] = $this->partner;
        } else if ($role  === $this->staffType) {
            $validatedData['role'] = $this->staff;
        } else {
            $validatedData['role'] = $this->admin;
        }
        $validatedData['password'] = Hash::make($request->password);
        $find_user = PlatformUser::where('email', $request->email)->first();
        if ($find_user) {
            return response()->json('Email is already exist');
        }
        $user = $this->platformUserInterface->store('PlatformUser', $validatedData);
        return new RolePermissionResource($user);
    }


    public function show(String $id)
    {
        $user_data = $this->platformUserInterface->findByID('PlatformUser', $id);
        if (!$user_data) {
            return response()->json([
                'message' => 'This user data is empty'
            ]);
        }
        if ($user_data['role'] === $this->individual || $user_data['role'] === $this->corporate) {
            return response()->json([
                'message' => 'This user is individual or corporate'
            ]);
        }
        return new RolePermissionResource($user_data);
    }

    public function update(AuthRequest $request, String $id)
    {
        $validatedData = $request->validated();
        if ($validatedData['role'] === $this->individualType || $validatedData['role'] === $this->corporateType) {
            return response()->json([
                'message' => 'This is the nornal and corporate user cannot chage role'
            ]);
        }
        $role = strtolower($request->role);
        if ($role  === $this->individualType) {
            $validatedData['role'] = $this->individual;
        } else if ($role  === $this->staffType) {
            $validatedData['role'] = $this->staff;
        } else {
            $validatedData['role'] = $this->admin;
        }

        $validatedData['password'] = Hash::make($request->password);
        $user_data = $this->platformUserInterface->findByID('PlatformUser', $id);
        if (!$user_data) {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }
        $user_data = $this->platformUserInterface->update('PlatformUser', $validatedData, $id);
        return new RolePermissionResource($user_data);
    }

    public function destroy(PlatformUser $platformUser)
    {
        return $this->platformUserInterface->delete('PlatformUser', $platformUser->id) ? response(status: 204) : response(status: 500);
    }

}
