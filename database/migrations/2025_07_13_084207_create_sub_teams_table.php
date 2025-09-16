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
        Schema::create('sub_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_team_id')->constrained()->onDelete('cascade'); // vínculo al equipo principal
            $table->foreignId('sub_team_name_id')->constrained('sub_team_names')->onDelete('cascade'); // nombre definido
            $table->foreignId('leader_id')->constrained('employees'); // jefe del subequipo
            $table->foreignId('pep_id')->nullable()->constrained('peps'); // puede heredar o cambiar
            $table->date('work_date'); // mismo día del equipo padre
            $table->boolean('active')->default(true);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{

    Schema::dropIfExists('sub_teams');
}
};
