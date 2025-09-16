<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('status_type_id')
            ->nullable()->after('active')
            ->constrained('status_types')
            ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['status_type_id']);
            $table->dropColumn('status_type_id');
        });
    }
};
