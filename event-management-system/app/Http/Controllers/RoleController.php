<?php

namespace App\Http\Controllers;

use App\Contracts\RoleInterface;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Common;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    private $roleInterface;
    public function __construct(RoleInterface $roleInterface)
    {
       $this->roleInterface = $roleInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::all();
        return RoleResource::collection($role);
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
    public function store(RoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = $this->roleInterface->store('Role',$validatedData);
        // $role = Common::create($validatedData);
        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 401);
        }
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();
        $role =  $this->roleInterface->findByID('Role',$role->id);
        if(!$role){
            return response()->json([
                'message' => 'Role not found'
            ],401);
        }
        $role = $this->roleInterface->update('Role',$validatedData, $role->id);
        // $role->update($validatedData);
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
       $this->roleInterface->delete('Role', $role->id)? response(status:204): response(status:500);
    }


    // public function destroy(Role $role)
    // {
    //     $role =  $this->roleInterface->findByID('Role',$role->id);
    //     if(!$role){
    //         return response()->json([
    //             'message' => 'Role not found'
    //         ],401);
    //     }
    //     $this->roleInterface->delete('Role',$role->id);
    //     // var_dump($role_data);
    //     // exit;
    //     // $role->update($validatedData);
    //     return response()->json([
    //         'message' => 'Role delete successfully'
    //     ]);
    // }
}
