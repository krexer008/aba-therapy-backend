<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'children';

    protected $fillable = [
        'full_name',
        'birth_date',
        'gender',
        'branch_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function therapy_sessions()
    {
        return $this->hasMany(TherapySession::class);
    }

    public function preferences()
    {
        return $this->hasMany(ChildPreference::class);
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'child_parents', 'child_id', 'parent_id')
            ->withPivot('relation')
            ->withTimestamps();
    }

    public function active_program()
    {
        return $this->hasOne(ChildProgram::class)->where('is_active', true);
    }
}
