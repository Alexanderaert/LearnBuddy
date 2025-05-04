<?php
// src/app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_mentor',
        'average_rating',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_mentor' => 'boolean',
        'average_rating' => 'float',
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

    public function reviews()
    {
        return $this->hasMany(Review::class, 'mentor_id');
    }

    public function updateAverageRating()
    {
        $average = $this->reviewsAsMentor()->avg('rating');
        $this->average_rating = $average ? round($average, 1) : null;
        $this->save();
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'mentor_id');
    }
}
