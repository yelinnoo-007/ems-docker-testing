<?php

namespace App\Http\Controllers;

use App\Contracts\TownshipInterface;
use App\Http\Requests\TownshipRequest;
use App\Http\Resources\TownshipResource;
use App\Models\Township;

class TownshipController extends Controller
{
    // private TownshipInterface $townshipInterface;
    public function __construct(private TownshipInterface $townshipInterface)
    {
        $this->townshipInterface = $townshipInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $township = $this->townshipInterface->all();
        return TownshipResource::collection($township);
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
    public function store(TownshipRequest $request)
    {
        $validatedData = $request->validated();
        $township = $this->townshipInterface->store('Township', $validatedData);
        if (!$township) {
            return response()->json([
                'message' => 'Failed to create township',
            ], 400);
        }
        return new TownshipResource($township);
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


    public function update(TownshipRequest $request, $id)
    {
        $validatedData = $request->validated();
        $township = $this->townshipInterface->findByID('Township', $id);
        if (!$township) {
            return response()->json([
                'message' => 'Township is not Found!',
            ], 400);
        }
        $township = $this->townshipInterface->update('Township', $validatedData, $id);
        return new TownshipResource($township);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Township $township)
    {
        return $this->townshipInterface->delete('Township', $township->id) ? response(status: 204) : response(status: 500);
    }
}
