<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'organizer_name',
        'title',
        'description',
        'venue',
        'start_time',
        'end_time',
        'poster_path',
        'has_certificate',
        'status',
        'admin_comment',
    ];
}
