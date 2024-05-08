<?php

use App\Models\Questionnaire\Questionnaire;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Romanlazko\Telegram\Models\TelegramChat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Questionnaire::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(TelegramChat::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->json('answers')->nullable();
            $table->boolean('finished')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
