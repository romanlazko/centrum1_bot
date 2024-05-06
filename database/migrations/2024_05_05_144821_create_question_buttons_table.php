<?php

use App\Models\Questionnaire\Question;
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
        Schema::create('question_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Question::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('text')->nullable();
            $table->string('value')->nullable();
            $table->string('type')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_buttons');
    }
};
