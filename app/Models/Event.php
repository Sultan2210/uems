<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'venue',
    'organizer_name',
    'start_time',
    'end_time',
    'description',
    'poster_path',
    'payment_qr_code', // Add this
    'has_certificate',
    'requires_payment',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Optionally, define the relationship to Student (for quick access)
    public function students()
    {
        return $this->belongsToMany(Student::class, 'registrations', 'event_id', 'matric_or_staff_no');
    }
}
