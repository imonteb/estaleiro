<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('daily_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pep_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->foreignId('leader_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('name');
            $table->string('work_type')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();

            $table->unique(['name', 'work_date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('daily_teams');
    }
};
