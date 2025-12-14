@extends('layouts.student')
@section('title','Payment | ' . $event->event_name)

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            {{-- BACK --}}
            <div class="mb-4">
                <a href="{{ route('student.events.show',$event->id) }}"
                   class="text-decoration-none text-muted fw-bold">
                    <i class="bi bi-arrow-left"></i> Back to Event
                </a>
            </div>

            {{-- PAYMENT FORM --}}
            <form method="POST"
                  action="{{ route('student.events.submit_payment',$event->id) }}"
                  enctype="multipart/form-data">

                @csrf

                {{-- Hidden user info --}}
                <input type="hidden" name="full_name" value="{{ request('full_name') }}">
                <input type="hidden" name="matric_or_staff_no" value="{{ request('matric_or_staff_no') }}">
                <input type="hidden" name="phone" value="{{ request('phone') }}">

                {{-- QR CARD --}}
                <div class="card border-0 shadow-sm mb-5"
                     style="border-radius:25px">

                    <div class="card-body text-center p-4">

                        <div class="mx-auto mb-3"
                             style="
                                width:260px;
                                height:260px;
                                border:8px solid #E91E63;
                                border-radius:20px;
                                padding:10px;
                                background:#fff;
                             ">

                            {{-- QR IMAGE --}}
                            <img src="{{ asset('assets/img/sample-qr.png') }}"
                                 class="w-100 h-100"
                                 alt="QR Code">
                        </div>

                        <div class="fw-bold text-white px-4 py-2 rounded-pill"
                             style="background:#E91E63;width:fit-content;margin:auto">
                            MALAYSIA NATIONAL QR
                        </div>

                    </div>
                </div>

                {{-- UPLOAD RECEIPT --}}
                <div class="mb-5">

                    <h5 class="fw-bold mb-3" style="color:#1A1A3D">
                        Upload Payment Proof
                    </h5>

                    <input type="file"
                           name="payment_receipt"
                           class="form-control form-control-lg"
                           accept="image/*"
                           required>

                    <div class="small text-muted mt-2">
                        Accepted format: JPG, PNG. Max 2MB.
                    </div>
                </div>

                {{-- SUBMIT --}}
                <button type="submit"
                        class="btn btn-lg w-100 text-white fw-bold py-3"
                        style="background:#1A1A3D;border-radius:8px">
                    Save & Continue
                </button>

            </form>

        </div>
    </div>
</div>

@endsection
