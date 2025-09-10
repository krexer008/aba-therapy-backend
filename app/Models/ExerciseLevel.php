<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exercise_id',
        'stage_name',
        'level', // 'PF', 'CHF', 'S'
        'order',
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function trials()
    {
        return $this->hasMany(Trial::class);
    }
}
