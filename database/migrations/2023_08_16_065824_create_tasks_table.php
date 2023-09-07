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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamp('deadline');
            $table->foreignId('assigned_to')->nullable()->constrained(app(\App\Models\User::class)->getTable())->onDelete('set null');
            $table->foreignId('assigned_by')->nullable()->constrained(app(\App\Models\User::class)->getTable())->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained(app(\App\Models\User::class)->getTable())->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained(app(\App\Models\User::class)->getTable())->onDelete('set null');
            $table->enum('status', ['open', 'in-progress', 'done'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
