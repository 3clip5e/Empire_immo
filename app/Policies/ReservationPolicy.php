<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;

class ReservationPolicy
{
    public function update(User $user, Reservation $reservation)
    {
        // bailleur who owns property or admin
        return $user->role === 'admin' || ($user->role === 'bailleur' && $reservation->property->user_id === $user->id);
    }
}
