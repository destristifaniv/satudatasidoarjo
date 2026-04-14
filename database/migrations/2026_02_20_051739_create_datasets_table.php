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
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('dssd_code'); // 1. Hapus ->unique() di sini
            $table->string('name');
            $table->text('description')->nullable(); // Pastikan nullable jika tidak di validasi
            $table->string('file_path');
            $table->string('category');
            $table->string('tags')->nullable();
            $table->year('year_start');
            $table->year('year_end');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Tambahkan kolom lain yang ada di Controller tapi belum ada di Migration ini
            $table->string('organization')->nullable();
            $table->string('unit')->nullable();
            $table->string('frequency')->nullable();
            $table->string('level')->nullable();
            $table->integer('downloads')->default(0);
            
            $table->timestamps();

            // 2. Tambahkan aturan unik gabungan Kode + Tahun Start
            $table->unique(['dssd_code', 'year_start'], 'dssd_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
