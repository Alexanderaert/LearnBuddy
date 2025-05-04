<?php
// src/database/migrations/2025_05_03_000002_add_average_rating_to_users.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 1)->nullable()->after('is_mentor'); // e.g., 4.5
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });
    }
};
