<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    public function abilities()
    {
        return $this->belongsToMany(Ability::class)->withPivot('created_at', 'updated_at');;
    }
    
    public function users():  BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}