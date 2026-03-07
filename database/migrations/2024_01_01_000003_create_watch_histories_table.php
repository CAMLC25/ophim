<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('watch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('movie_slug');
            $table->string('movie_name');
            $table->string('poster_url')->nullable();
            $table->string('episode')->default('1');
            $table->timestamp('watched_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'movie_slug']);
            $table->index('movie_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_histories');
    }
};
