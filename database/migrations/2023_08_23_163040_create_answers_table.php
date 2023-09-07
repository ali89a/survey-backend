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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer');
            $table->foreignId('feedback_id')->nullable()->constrained(app(\App\Models\Feedback::class)->getTable())->onDelete('set null');
            $table->foreignId('survey_id')->nullable()->constrained(app(\App\Models\Survey::class)->getTable())->onDelete('set null');
            $table->foreignId('question_id')->nullable()->constrained(app(\App\Models\Question::class)->getTable())->onDelete('set null');
            $table->timestamps();
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
