<?php
// database/migrations/2025_05_04_000001_create_materials_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // Для PDF/видео
            $table->string('url')->nullable(); // Для ссылок
            $table->string('category'); // Например, "Программирование: Основы Python"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
