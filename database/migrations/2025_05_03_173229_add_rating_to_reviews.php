<?php
// src/database/migrations/2025_05_03_173229_add_rating_to_reviews.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->unsignedInteger('rating')->nullable()->after('comment'); // 1-5
            }
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};
