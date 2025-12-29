<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',               // if you use it anywhere
        'full_name',
        'matric_or_staff_no',
        'department',
        'phone',
        'status',
        'proof_of_payment',      // if you use payment
    ];

    // relationships (optional but recommended)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}

}
