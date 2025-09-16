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
        Schema::create('sub_team_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_team_id')->constrained()->onDelete('cascade');     // vínculo al subequipo
            $table->foreignId('vehicle_id')->constrained('vehicles');                 // vehículo asignado
            $table->string('status')->nullable();                                     // opcional (ej. reserva, uso parcial)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_team_vehicles');
    }
};
