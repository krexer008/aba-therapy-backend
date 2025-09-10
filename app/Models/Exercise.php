<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'skill_id'];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function exrcise_level()
    {
        return $this->hasMany(ExerciseLevel::class);
    }
}
