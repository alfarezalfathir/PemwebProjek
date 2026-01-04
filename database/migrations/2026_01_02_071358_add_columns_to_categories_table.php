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
        // Cek dulu, kalau kolom 'description' BELUM ADA, baru buat
        Schema::table('categories', function (Blueprint $table) {
        // Cek dulu, kalau kolom 'description' BELUM ADA, baru buat
        if (!Schema::hasColumn('categories', 'description')) {
            $table->text('description')->nullable()->after('name'); 
        }

        // Cek dulu, kalau kolom 'is_active' BELUM ADA, baru buat
        if (!Schema::hasColumn('categories', 'is_active')) {
            $table->boolean('is_active')->default(true)->after('description');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
