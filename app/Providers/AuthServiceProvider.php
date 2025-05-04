<?php

namespace App\Providers;

use App\Policies\MentorPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'mentor' => MentorPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('send-message', function ($user) {
            return true; // Все авторизованные пользователи могут отправлять сообщения
        });

        Gate::define('view-messages', function ($user) {
            return true; // Все авторизованные пользователи могут просматривать сообщения
        });

        Gate::define('is-mentor', function ($user) {
            return $user->is_mentor;
        });
    }
}
