@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Guest List</div>
                <div class="card-body">
                    @foreach ($qr_tickets as $key => $qr_ticket)
                        <div class="d-flex">
                            <div class="mb-3">
                                {{ $qrCodeImages[$key] }}
                            </div>
                            <div class="pt-4 m-auto">
                                <ul>
                                    <li>Event Name: {{ $qr_ticket->event_name }}</li>
                                    <li>Name: {{ $qr_ticket->first_name }} {{ $qr_ticket->middle_name }} {{ $qr_ticket->last_name }}</li>
                                    <li>Phone_no: {{ $qr_ticket->phone_no }}</li>
                                    <li>Email: {{ $qr_ticket->email }}</li>
                                </ul>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
