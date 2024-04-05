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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('area_name', 30);
            $table->time('mañana_start_time')->nullable();
            $table->time('mañana_end_time')->nullable();
            $table->time('tarde_start_time')->nullable();
            $table->time('tarde_end_time')->nullable();
            $table->time('noche_start_time')->nullable();
            $table->time('noche_end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
