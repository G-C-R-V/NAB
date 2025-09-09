<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 0=Sun..6=Sat
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->date('date');
            $table->boolean('is_closed')->default(false);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_exceptions');
        Schema::dropIfExists('schedules');
    }
};

