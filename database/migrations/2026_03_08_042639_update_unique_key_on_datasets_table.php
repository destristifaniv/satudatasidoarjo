<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('datasets', function (Blueprint $table) {
            // 1. Hapus aturan unik yang lama
            $table->dropUnique('dssd_year_unique');
            
            // 2. Buat aturan unik baru yang menggabungkan 3 kolom (termasuk user_id)
            $table->unique(['dssd_code', 'year_start', 'user_id'], 'dssd_year_user_unique');
        });
    }

    public function down()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropUnique('dssd_year_user_unique');
            $table->unique(['dssd_code', 'year_start'], 'dssd_year_unique');
        });
    }
};