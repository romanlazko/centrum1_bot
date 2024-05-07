<?php

use App\Models\Tag;
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
        Schema::create('telegram_chat_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TelegramChat::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Tag::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_chat_tags');
    }
};
