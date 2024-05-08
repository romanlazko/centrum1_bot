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
        Schema::table('telegram_chats', function (Blueprint $table) {
            $table->after('last_name', function (Blueprint $table) {
                $table->string('profile_first_name')->nullable();
                $table->string('profile_last_name')->nullable();
                $table->string('profile_phone')->nullable();
                $table->date('communication_date')->nullable();
                $table->date('issue_date')->nullable();
                $table->boolean('is_communicated')->nullable();
                $table->text('notes')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegram_chats', function (Blueprint $table) {
            $table->dropColumn(['profile_first_name', 'profile_last_name', 'profile_phone', 'communication_date', 'issue_date', 'is_communicated', 'notes']);
        });
    }
};
