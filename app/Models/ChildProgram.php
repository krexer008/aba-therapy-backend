<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'child_id',
        'curator_id',
        'is_active',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function curator()
    {
        return $this->belongsTo(User::class, 'curator_id');
    }

    public function program_exercises()
    {
        return $this->hasMany(ProgramExercise::class);
    }
}
