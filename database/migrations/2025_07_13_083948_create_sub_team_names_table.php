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
        Schema::create('sub_team_names', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();         // nombre del subgrupo
            $table->boolean('active')->default(true); // para ocultar sin eliminar
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_team_names');
    }
};
