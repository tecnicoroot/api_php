<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

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
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getJWTIdentifier() {

        return $this->getKey();
    }

    public function getJWTCustomClaims() {

        return [

            'name' => $this->name,
            'Rules' => [
                'Administrador do Sistema'
            ]

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