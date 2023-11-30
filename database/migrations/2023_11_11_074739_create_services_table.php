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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number');
            $table->foreignId('service_type_id');
            $table->foreignId('vehicle_id');
            $table->foreignId('user_id');
            $table->foreignId('route_id')->nullable();
            $table->string('description');
            $table->string('status');
            $table->string('duration_in_hours')->nullable();
            $table->float('estimated_cost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
