<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Event Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1A1A3D;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .event-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #1A1A3D;
        }
        .registration-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        h1 {
            color: white;
            margin: 0;
        }
        h2 {
            color: #1A1A3D;
            margin-top: 0;
        }
        .detail-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Event Ticket</h1>
    </div>

    <div class="content">
        <p>Hello <strong>{{ $registration->full_name }}</strong>,</p>

        <p>Congratulations! Your payment has been verified and your ticket for <strong>{{ $event->event_name }}</strong> is confirmed.</p>

        <div class="event-details">
            <h2>Event Details</h2>

            <div class="detail-row">
                <span class="detail-label">Event Name:</span> {{ $event->event_name }}
            </div>

            @if($event->start_at)
            <div class="detail-row">
                <span class="detail-label">Date & Time:</span>
                {{ $event->start_at->format('l, d F Y') }}
                @if($event->start_at && $event->end_at)
                    from {{ $event->start_at->format('h:i A') }} to {{ $event->end_at->format('h:i A') }}
                @endif
            </div>
            @endif

            @if($event->location)
            <div class="detail-row">
                <span class="detail-label">Venue:</span> {{ $event->location }}
            </div>
            @endif

            @if($event->venue && $event->venue !== $event->location)
            <div class="detail-row">
                <span class="detail-label">Location:</span> {{ $event->venue }}
            </div>
            @endif
        </div>

        <div class="registration-details">
            <h2>Registration Details</h2>

            <div class="detail-row">
                <span class="detail-label">Name:</span> {{ $registration->full_name }}
            </div>

            @if($registration->matric_or_staff_no)
            <div class="detail-row">
                <span class="detail-label">Matric/Staff Number:</span> {{ $registration->matric_or_staff_no }}
            </div>
            @endif

            @if($registration->department)
            <div class="detail-row">
                <span class="detail-label">Department:</span> {{ $registration->department }}
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Registration Status:</span> {{ ucfirst($registration->status) }}
            </div>
        </div>

        <p><strong>Please present this email or your registration details at the event venue.</strong></p>

        <p>We look forward to seeing you there!</p>

        <p>If you have any questions, please contact the event organizer.</p>

        <p>Thank you,<br>
        <strong>{{ config('app.name') }} Team</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>
