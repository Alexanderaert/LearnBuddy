<?php
// tests/Feature/MessageTest.php
namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message()
    {
        /** @var User $sender */
        $sender = User::factory()->create();
        /** @var User $receiver */
        $receiver = User::factory()->create();

        // Создаём бронирование
        $sender->bookingsAsStudent()->create([
            'mentor_id' => $receiver->id,
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
            'status' => 'confirmed',
        ]);

        $token = $sender->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        Notification::fake();

        $response = $this->postJson('/api/messages', [
            'receiver_id' => $receiver->id,
            'message' => 'Hello, can we discuss the lesson?',
        ]);

        $response->assertStatus(201, 'Failed to send message: ' . $response->getContent())
            ->assertJson([
                'message' => 'Message sent successfully',
                'data' => [
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'message' => 'Hello, can we discuss the lesson?',
                ],
            ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => 'Hello, can we discuss the lesson?',
        ]);

        Notification::assertSentTo($receiver, NewMessageNotification::class, function ($notification) use ($sender, $receiver) {
            $data = $notification->toDatabase($receiver);
            return is_array($data) ? $data['sender_id'] === $sender->id : $data->data['sender_id'] === $sender->id;
        });
    }

    public function test_user_cannot_send_message_to_self()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $token = $user->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        $response = $this->postJson('/api/messages', [
            'receiver_id' => $user->id,
            'message' => 'Hello, me!',
        ]);

        $response->assertStatus(422, 'Failed to send message: ' . $response->getContent())
            ->assertJson(['message' => 'Cannot send message to yourself']);
    }

    public function test_user_can_retrieve_messages()
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        /** @var User $user2 */
        $user2 = User::factory()->create();

        // Создаём бронирование
        $user1->bookingsAsStudent()->create([
            'mentor_id' => $user2->id,
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
            'status' => 'confirmed',
        ]);

        Message::factory()->create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'message' => 'Hi!',
        ]);
        Message::factory()->create([
            'sender_id' => $user2->id,
            'receiver_id' => $user1->id,
            'message' => 'Hello back!',
        ]);

        $token = $user1->createToken('test-token')->plainTextToken;
        $this->withHeaders(['Authorization' => "Bearer $token"]);

        $response = $this->getJson("/api/messages/{$user2->id}");

        $response->assertStatus(200, 'Failed to retrieve messages: ' . $response->getContent())
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['message' => 'Hi!'])
            ->assertJsonFragment(['message' => 'Hello back!']);
    }

    public function test_user_receives_notification_for_new_message()
    {
        /** @var User $sender */
        $sender = User::factory()->create();
        /** @var User $receiver */
        $receiver = User::factory()->create();

        // Создаём бронирование
        $booking = $sender->bookingsAsStudent()->create([
            'mentor_id' => $receiver->id,
            'start_time' => '2025-05-04 10:00:00',
            'end_time' => '2025-05-04 11:00:00',
            'status' => 'confirmed',
        ]);

        // Аутентификация для отправителя
        $senderToken = $sender->createToken('sender-token')->plainTextToken;
        $this->actingAs($sender, 'sanctum');
        $this->withHeaders(['Authorization' => "Bearer $senderToken"]);

        $response = $this->postJson('/api/messages', [
            'receiver_id' => $receiver->id,
            'message' => 'Hello, can we discuss the lesson?',
        ]);

        $response->assertStatus(201, 'Failed to send message: ' . $response->getContent());

        // Проверяем, что уведомление сохранено в базе данных
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $receiver->id,
            'notifiable_type' => 'App\Models\User',
            'type' => 'App\Notifications\NewMessageNotification',
        ]);

        // Аутентификация для получателя
        $receiverToken = $receiver->createToken('receiver-token')->plainTextToken;
        $this->actingAs($receiver, 'sanctum');
        $this->withHeaders(['Authorization' => "Bearer $receiverToken"]);

        $response = $this->getJson('/api/notifications');

        $response->assertStatus(200, 'Failed to retrieve notifications: ' . $response->getContent())
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'message' => 'Hello, can we discuss the lesson?',
            ]);
    }
}
