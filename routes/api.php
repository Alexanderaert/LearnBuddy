<?php
// src/routes/api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MentorProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::post('/schedule', [MentorProfileController::class, 'storeSchedule']);

    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/messages/{user_id}', [MessageController::class, 'show']);
    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/skills', [SkillController::class, 'index']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);

    Route::get('/bookings', [StudentController::class, 'bookings']);
    Route::get('/mentor/bookings', [MentorProfileController::class, 'bookings']);
    Route::get('/mentor/reviews', [MentorProfileController::class, 'reviews']);
    Route::post('/mentors/{id}/book', [BookingController::class, 'store']);

    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::get('/materials/{material}/download', [MaterialController::class, 'download']);
});

Route::get('/mentors', [MentorProfileController::class, 'index']);
Route::get('/mentors/top', [MentorProfileController::class, 'top']);
Route::get('/mentors/recommended', [MentorProfileController::class, 'recommended']);
Route::get('/mentors/{id}', [MentorProfileController::class, 'show']);
Route::get('/mentors/{id}/schedule', [MentorProfileController::class, 'mentorSchedule']);
