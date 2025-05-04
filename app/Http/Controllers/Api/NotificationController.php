<?php
// src/app/Http/Controllers/Api/NotificationController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $notifications = $user->notifications()->get();


        return response()->json([
            'message' => 'Notifications retrieved successfully',
            'data' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'sender_id' => $notification->data['sender_id'],
                    'sender_name' => $notification->data['sender_name'],
                    'message' => $notification->data['message'],
                    'created_at' => $notification->created_at,
                ];
            })->toArray(),
        ]);
    }
}
