<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'title',
        'venue',
        'location',
        'organizer_name',
        'organizer_id',
        'created_by',
        'start_time',
        'start_at',
        'end_time',
        'end_at',
        'description',
        'poster_path',
        'poster',
        'payment_qr_code',
        'has_certificate',
        'requires_payment',
        'status',
        'capacity',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
}
