<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('analytics_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('social_post_id')
                ->constrained('social_posts_queue')
                ->cascadeOnDelete();

            $table->string('provider');

            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('comments')->default(0);

            $table->json('raw_response')->nullable();

            $table->timestamps();

            $table->index(['provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_logs');
    }
};

