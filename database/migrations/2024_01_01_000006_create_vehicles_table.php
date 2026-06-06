<?php

namespace App\Database\Migrations;

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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->string('year');
            $table->string('plate')->unique();
            $table->string('color')->nullable();
            $table->string('vin')->unique()->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->enum('engine_type', ['gasoline', 'diesel', 'electric', 'hybrid'])->default('gasoline');
            $table->enum('transmission', ['automatic', 'manual', 'cvt'])->default('manual');
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'in_service', 'sold', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('client_id');
            $table->index('plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
