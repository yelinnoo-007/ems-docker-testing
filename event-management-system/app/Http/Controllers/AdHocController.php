<?php

namespace App\Http\Controllers;

use App\Contracts\AdHocInterface;
use App\Contracts\QrTicketInterface;
use App\Http\Requests\AdHocRequest;
use App\Http\Controllers\QrTickerController;
use App\Http\Resources\AdHocResource;
use App\Listeners\ProcessAdHocStored;
use App\Models\AdHoc;
use App\Models\QrTicket;

class AdHocController extends Controller
{
    public function __construct(private AdHocInterface $adHocInterface, private QrTicketInterface $qrTicketInterface)
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(AdHoc::class, 'adhoc');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adHoc = $this->adHocInterface->all();
        return AdHocResource::collection($adHoc);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function store(AdHocRequest $request)
    {
        $validatedData = $request->validated();
        $adHoc = $this->adHocInterface->store('AdHoc', $validatedData);

        if (!$adHoc) {
            return response()->json([
                'message' => 'Something went wring and please try again.'
            ], 400);
        } else {
            $qrTicket = [];
            $qrTicket['ad_hoc_id'] = $adHoc->id;
            $qrTicket['qr_code'] = rand();
            $qrTicket = $this->qrTicketInterface->store("QrTicket", $qrTicket);
        }
        return new AdHocResource($adHoc);
    }



    public function update(AdHocRequest $request, AdHoc $adhoc)
    {
        $validatedData = $request->validated();
        $adHoc = $this->adHocInterface->findByID('AdHoc', $adhoc->id);
        if (!$adHoc) {
            return response()->json([
                'message' => 'AdHoc is not found'
            ], 400);
        }
        $adHoc = $this->adHocInterface->update('AdHoc', $validatedData, $adhoc->id);

        return new AdHocResource($adHoc);
    }

    public function destroy(AdHoc $adhoc)
    {
        $adHoc = $this->adHocInterface->findByID('AdHoc', $adhoc->id);
        if (!$adHoc) {
            return response()->json([
                'message' => 'AdHoc can not Delete successfully'
            ], 400);
        }

        if ($this->adHocInterface->delete('AdHoc', $adhoc->id)) {
            $qr_ticket = QrTicket::where('id', $adhoc->id)->first();
            //$this->qrTicketInterface->delete($qr_ticket->id);
            $this->qrTicketInterface->delete("QrTicket", $qr_ticket->id);
            return new AdHocResource($adHoc);
        }
        return response(['message' => 'error']);
    }
}
