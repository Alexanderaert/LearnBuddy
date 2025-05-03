<?php
// routes/api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\MentorProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/mentors/{id}/book', [BookingController::class, 'store']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/bookings', [StudentController::class, 'bookings']);
    Route::get('/mentor/bookings', [MentorProfileController::class, 'bookings']);
    Route::get('/mentor/reviews', [MentorProfileController::class, 'reviews']);
    Route::post('/schedule', [MentorProfileController::class, 'storeSchedule']);
    Route::get('/skills', [SkillController::class, 'index']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);
});

Route::get('/mentors', [MentorController::class, 'index']);
Route::get('/mentors/{id}', [MentorController::class, 'show']);
Route::get('/mentors/{id}/schedule', [MentorProfileController::class, 'mentorSchedule']);
