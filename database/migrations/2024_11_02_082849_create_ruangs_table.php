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
        Schema::create('ruangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruang');
            $table->foreignId('gedung_id')
                  ->constrained('gedungs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->decimal('ukuran', 10, 2);        
            $table->string('kondisi');  
            $table->string('peruntukkan');
            $table->text('keterangan')->nullable();    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangs');
    }
};
