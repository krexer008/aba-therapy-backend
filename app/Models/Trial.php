<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id',
        'exercise_level_id',
        'is_successful',
        'behavior_notes',
    ];

    public function therapy_session()
    {
        return $this->belongsTo(TherapySession::class, 'session_id');
    }

    public function exercise_level()
    {
        return $this->belongsTo(ExerciseLevel::class);
    }
}
