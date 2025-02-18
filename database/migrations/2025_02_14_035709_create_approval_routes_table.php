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
        Schema::create('approval_routes', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // misalnya 'item'
            $table->integer('step');  // misalnya 1 atau 2
            $table->string('type')->nullable(); // misalnya 'produksi1' atau 'produksi2'
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_routes');
    }
};
