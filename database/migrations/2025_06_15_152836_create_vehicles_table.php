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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('car_plate')->unique();
            $table->foreignId('vehicle_brand_id')
                ->constrained('vehicle_brands')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('model');
            $table->string('type');
            $table->string('fuel_card_number')->unique();
            $table->string('fuel_card_pin')->unique();
            $table->string('insurance_name')->index();
            $table->date('insurance_validity_date')->nullable()->index();
            $table->date('last_vehicle_inspection_date')->nullable()->index();
            $table->string('vehicle_condition')->nullable()->index();
            $table->boolean('assigned')->default(false)->index();
            $table->string('image_url')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
