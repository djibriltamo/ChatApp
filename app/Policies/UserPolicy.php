<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function talkTo(User $user, User $to)
    {
        return $user->id !== $to->id;
    }
}
