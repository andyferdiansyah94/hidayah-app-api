<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('nama_barang');
        });

        Schema::table('penjualans', function (Blueprint $table) {
            // Tambahkan kolom baru dengan tipe JSON
            $table->json('nama_barang')->default(json_encode([]))->after('id');
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn('nama_barang');
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->string('nama_barang')->nullable()->after('id');
        });
    }
};

