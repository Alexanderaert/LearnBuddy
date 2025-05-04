<?php
// database/migrations/2025_05_04_000002_create_material_skill_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_skill');
    }
};
