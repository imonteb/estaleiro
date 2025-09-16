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
        Schema::table('daily_teams', function (Blueprint $table) {
            $table->foreignId('team_name_id')->nullable()->constrained('teams_names_tables')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_teams', function (Blueprint $table) {
            $table->dropForeign(['team_name_id']);
            $table->dropColumn('team_name_id');
        });
    }
};
