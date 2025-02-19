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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('produksi');
            $table->string('kode_item');
            $table->string('nama_item');
            $table->string('jenis');
            $table->string('kondisi');
            $table->string('kode_lokasi');
            $table->string('nama_lokasi');
            $table->decimal('jumlah', 8, 2);
            $table->string('gambar'); // Menyimpan nama file gambar
            $table->integer('approval_level')->default(0);
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
