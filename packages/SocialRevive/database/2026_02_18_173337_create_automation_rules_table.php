<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->integer('min_days_old')->default(30);
            $table->integer('interval_minutes')->default(60);

            $table->text('template');

            $table->integer('avoid_repeat_days')->default(7);

            $table->string('timezone')->default('UTC');

            $table->boolean('ai_caption')->default(false);
            $table->boolean('auto_hashtag')->default(false);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_rules');
    }
};

