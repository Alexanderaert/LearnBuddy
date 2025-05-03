<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'mentor_id',
        'student_id',
        'start_time',
        'end_time',
        'status',
    ];
}
