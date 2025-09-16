<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('status_types', function (Blueprint $table) {
            $table->boolean('is_default')->default(false); // ✅ Campo para marcar el valor por defecto
        });
    }

    public function down(): void {
        Schema::table('status_types', function (Blueprint $table) {
            $table->dropForeign(['status_type_id']);
            $table->dropColumn('status_type_id');
        });
    }
};

