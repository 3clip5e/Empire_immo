<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function properties() {
        return $this->hasMany( Property::class );
    }

    public function initials() {
        $parts = explode( ' ', trim( $this->name ) );
        $initials = '';
        foreach ( array_slice( $parts, 0, 2 ) as $p ) $initials .= strtoupper( $p[ 0 ] ?? '' );
        return $initials;
    }

    public function reservations() {
        return $this->hasMany( Reservation::class );
    }


}
