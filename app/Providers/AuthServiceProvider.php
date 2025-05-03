<?php

namespace App\Providers;

use App\Policies\MentorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'mentor' => MentorPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
