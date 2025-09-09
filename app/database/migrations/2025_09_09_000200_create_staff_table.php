<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('display_name');
            $table->text('bio')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('staff_services', function (Blueprint $table) {
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->primary(['staff_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_services');
        Schema::dropIfExists('staff');
    }
};

