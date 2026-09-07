<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_posts_queue', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('social_account_id')
                ->constrained('social_accounts')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('post_id');

            $table->text('caption')->nullable();
            $table->json('media')->nullable(); // multiple images

            $table->json('utm_data')->nullable();

            $table->timestamp('scheduled_at');
            $table->timestamp('posted_at')->nullable();

            $table->string('status')
                ->default('pending'); // pending, processing, posted, failed

            $table->integer('retry_count')->default(0);
            $table->text('last_error')->nullable();

            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
            $table->index(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_posts_queue');
    }
};
