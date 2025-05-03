<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class MentorPolicy
{
    public function isMentor(User $user): Response
    {
        return $user->is_mentor
            ? Response::allow()
            : Response::deny('You are not a mentor.');
    }
}
