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
            $table->unsignedBigInteger('produksi_id')->nullable();
            $table->foreign('produksi_id')->references('id')->on('master_produksi')->nullOnDelete();
            $table->string('kode_item');
            $table->string('nama_item');
            $table->string('satuan');
            $table->string('jenis');
            $table->string('kondisi');
            $table->string('kode_lokasi')->nullable();
            $table->string('nama_lokasi')->nullable();
            $table->string('detail_lokasi')->nullable();
            $table->decimal('jumlah', 8, 2);
            $table->text('gambar')->nullable(); // Menyimpan nama file gambar
            $table->integer('approval_level')->default(0);
            $table->string('status')->default('Draft');
            $table->string('keterangan')->nullable();
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
