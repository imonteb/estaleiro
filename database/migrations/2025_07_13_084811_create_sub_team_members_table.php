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
        Schema::create('sub_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_team_id')->constrained()->onDelete('cascade'); // vínculo al subequipo
            $table->foreignId('employee_id')->constrained('employees');           // colaborador asignado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_team_members');
    }
};
