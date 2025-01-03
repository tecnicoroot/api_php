<?php

namespace App\Models;

use App\Traits\Observers\UserObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, UserObserver;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','client_id', 'client_secret'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    // Liga o usuários as suas rules
    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRoles($role){
        return  $this->roles()->where('name', $role)->exists();
    }

    public function getJWTIdentifier() {

        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        // Pega as abilities e adiciona NO jwt
        $abilities = $this->roles->map->abilities->flatten()->pluck('name');
        
        
        return [

            'name' => $this->name,
            'Rules' => $abilities
        ];

     }

     public function setPasswordAttribute($val){
         $pass = Hash::make(($val));
         $this->attributes['password'] = $pass;
     }
     public function setClientIdAttribute($val){
        $this->attributes['client_id'] = 'client_id_'.$val;
    }

    public function setClientSecretAttribute($val){
        $this->attributes['client_secret'] = 'client_secret_'.$val;
    }
}