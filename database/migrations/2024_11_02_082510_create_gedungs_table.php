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
        Schema::create('gedungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung');
            $table->decimal('luas_gedung', 10, 2);   
            $table->year('tahun_perolehan');        
            $table->decimal('nilai_bangunan', 15, 2);
            $table->integer('jumlah_ruang')->default(0);  
            $table->string('peruntukkan');
            $table->string('gambar')->nullable();    
            $table->text('keterangan')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gedungs');
    }
};
