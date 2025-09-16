<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('daily_team_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_team_id')->constrained('daily_teams')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_team_id')->nullable()->constrained('sub_teams')->nullOnDelete();
            $table->timestamps();

            $table->unique(['daily_team_id', 'vehicle_id']);
        });
    }

   

    public function down(): void {
        Schema::dropIfExists('daily_team_vehicles');
    }
};
