<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildPreference extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'child_id',
        'type',
        'description',
    ];

    // Связь: предпочтение принадлежит ребенку
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
