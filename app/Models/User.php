<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    function galaxPayParametros()
    {
        return $this->hasOne(galaxpay_parametros::class);
    }

    function galaxPayClientes()
    {
        return $this->hasMany(clientes_galaxpay::class);
    }

    function userPrimario()
    {
        return $this->hasOne(empresas_parceiras::class, 'user_id');
    }

    function empresasAssociadas()
    {
        return $this->hasMany(empresas_parceiras::class, 'user_linked_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
