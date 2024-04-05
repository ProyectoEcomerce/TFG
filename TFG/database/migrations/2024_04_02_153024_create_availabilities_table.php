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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('n_day');
            $table->set('avaibility', ['manana', 'tarde', 'noche']);
            $table->foreignID('user_id')->references('id')->on('users');
            $table->foreignID('week_id')->references('id')->on('weeks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
