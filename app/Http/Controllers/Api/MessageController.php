<?php
// src/app/Http/Controllers/Api/MessageController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('send-message');

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Проверка на отправку сообщения самому себе
        if ($user->id === $validated['receiver_id']) {
            return response()->json([
                'message' => 'Cannot send message to yourself',
            ], 422);
        }

        // Проверяем, есть ли бронирование между отправителем и получателем
        $hasBooking = $user->bookingsAsStudent()->where('mentor_id', $validated['receiver_id'])->exists()
            || $user->bookingsAsMentor()->where('student_id', $validated['receiver_id'])->exists();


        if (!$hasBooking) {
            return response()->json([
                'message' => 'You can only message users with whom you have a booking.',
            ], 403);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        // Отправляем уведомление получателю
        $receiver = User::find($validated['receiver_id']);

        $receiver->notify(new NewMessageNotification($message));

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender:id,name', 'receiver:id,name']),
        ], 201);
    }

    public function show($userId): JsonResponse
    {
        Gate::authorize('view-messages');

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Проверяем, есть ли бронирование
        $hasBooking = $user->bookingsAsStudent()->where('mentor_id', $userId)->exists()
            || $user->bookingsAsMentor()->where('student_id', $userId)->exists();

        if (!$hasBooking) {
            return response()->json([
                'message' => 'You can only view messages with users with whom you have a booking.',
            ], 403);
        }

        $messages = Message::where(function ($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($user, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'Messages retrieved successfully',
            'data' => $messages,
        ]);
    }
}
