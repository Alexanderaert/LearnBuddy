<?php
// src/app/Models/Review.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'student_id',
        'comment',
        'rating',
    ];

    protected static function booted()
    {
        static::created(function ($review) {
            $review->mentor->updateAverageRating();
        });

        static::deleted(function ($review) {
            $review->mentor->updateAverageRating();
        });
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
