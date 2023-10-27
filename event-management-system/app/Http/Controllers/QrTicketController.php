<?php

namespace App\Http\Controllers;

use App\Contracts\QrTicketInterface;
use App\Http\Requests\QrTicketRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\QrTicketResource;
use App\Models\Booking;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Event;
use App\Models\Qrgenerate;
use App\Models\QrTicket;
use Illuminate\Http\Request;

class QrTicketController extends Controller
{
    public function __construct(private QrTicketInterface $qrTicketInterface)
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $qr_tickets = $this->qrTicketInterface->all();
        // return QrTicketResource::collection($qr_tickets);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store()
    {
        // return $this->adHoc_id;
        // Use $adHocId to perform your logic
        // For example, retrieve the AdHoc entity by ID and process it
        // $validatedData = $request->validated();
        // $ad_hoc_id = AdHoc::find($this->adHoc_id);


        // $validatedData['ad_hoc_id'] = $ad_hoc_id;
        // $randomBytes = random_bytes(16);
        // $validatedData['qr_code'] = bin2hex($randomBytes);

        // $qr_ticket = $this->qrTicketInterface->store($validatedData);

        // if (!$qr_ticket) {
        //     return response()->json([
        //         'message' => 'Failed to create Qr Code Ticket'
        //     ], 400);
        // }
        // return new QrTicketResource($qr_ticket);
    }

    public function show(QrTicket $qr_ticket)
    {
        $venueEventData = Booking::with(['event', 'venue.venueImages'])->where('event_id', $qr_ticket->ad_hoc->event->id)->first();
        $qr_tickets = Qrgenerate::where('event_id', $qr_ticket->ad_hoc->event->id)->get();
        $qrCodeImages = [];
        foreach ($qr_tickets as $key => $qr_ticket) {
            $qrCode = QrCode::size(150)->generate($qr_ticket);
            $qrCodeImages[$key] =  $qrCode;
        }

        return response()->json([
            //'qrTickets' => $qr_tickets,
            'qrCodeImages' => $qrCodeImages,
            'venueEventData' => new BookingResource($venueEventData)
        ], 200);
        return view('home', compact('qrCodeImages', 'qr_tickets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QrTicketRequest $request, string $id)
    {
        // $validatedData = $request->validated();
        // $qr_ticket = $this->qrTicketInterface->findByID('QrTicket', $id);

        // // $qr_ticket = QrTicket::find($id);
        // if (!$qr_ticket) {
        //     return response()->json([
        //         'message' => "Your Township is not found!",
        //     ], 400);
        // }
        // $qr_ticket = $this->qrTicketInterface->update('QrTicket', $validatedData, $id);

        // // $qr_ticket->update($validatedData);
        // return new QrTicketResource($qr_ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $qr_ticket = $this->qrTicketInterface->findByID('QrTicket', $id);

        // //$qr_ticket = QrTicket::find($id);
        // if (!$qr_ticket) {
        //     return response()->json([
        //         'message' => 'Qr Ticket is not Found and Failed to Delete'
        //     ], 400);
        // }
        // //$township = $this->townshipInterface->delete($id);
        // $qr_ticket = $this->qrTicketInterface->delete('QrTicket', $id);
        // // $qr_ticket->delete();
        // return new QrTicketResource($qr_ticket);
    }
}
