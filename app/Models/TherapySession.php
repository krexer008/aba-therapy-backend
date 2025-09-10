<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TherapySession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'therapy_sessions';
    protected $fillable = [
        'child_id',
        'therapist_id',
        'room_id',
        'date_time',
        'duration_minutes',
        'selected_tms',
        'notes',
    ];
}
