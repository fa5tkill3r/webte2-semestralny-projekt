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
        Schema::create('set_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')->constrained('sets');
            $table->foreignId('task_id')->constrained('tasks');
            $table->integer('max_points');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_sets');
    }
};
