<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramExercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_id',
        'exercise_id',
        'order',
        'target_level',
        'is_completed',
    ];

    public function program()
    {
        return $this->belongsTo(ChildProgram::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
