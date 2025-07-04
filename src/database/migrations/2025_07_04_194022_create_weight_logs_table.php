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
        Schema::create('weight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date')->comment('日付');
            $table->decimal('weight', 4, 1)->comment('体重');
            $table->integer('calories_intake')->nullable()->comment('食事量');
            $table->time('exercise_time')->nullable()->comment('運動時間');
            $table->text('exercise_content')->nullable()->comment('運動内容');
            $table->timestamps();
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_logs');
    }
};