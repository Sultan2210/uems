<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'event_name' => 'Tech Talk: AI in 2025',
            'description'=> 'Intro to AI trends and careers.',
            'location'   => 'ADM LT1',
            'start_at'   => now()->addDays(2)->setTime(10, 0),
            'end_at'     => now()->addDays(2)->setTime(12, 0),
            'capacity'   => 150,
            'status'     => 'approved',     // published to students
            'created_by' => 1,              // organizer user id
            'poster'     => null,
        ]);

        Event::create([
            'event_name' => 'Entrepreneurship Forum',
            'description'=> 'Panel with alumni founders.',
            'location'   => 'MAIN HALL',
            'start_at'   => now()->addDays(5)->setTime(14, 0),
            'end_at'     => now()->addDays(5)->setTime(16, 0),
            'capacity'   => 200,
            'status'     => 'approved',
            'created_by' => 1,
            'poster'     => null,
        ]);
    }
}
