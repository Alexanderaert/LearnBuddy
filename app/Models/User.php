<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_mentor',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'mentor_skill', 'mentor_id', 'skill_id');
    }

    public function bookingsAsMentor()
    {
        return $this->hasMany(Booking::class, 'mentor_id');
    }

    public function bookingsAsStudent()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function reviewsAsMentor()
    {
        return $this->hasMany(Review::class, 'mentor_id');
    }

    public function reviewsAsStudent()
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'mentor_id');
    }
}
