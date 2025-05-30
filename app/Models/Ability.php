<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('created_at', 'updated_at');
    }
}