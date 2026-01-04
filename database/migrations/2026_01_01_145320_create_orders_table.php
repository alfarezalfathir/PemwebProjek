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
        Schema::create('orders', function (Blueprint $table) {
           $table->id();
        // Foreign Keys
            $table->foreignId('user_id')->constrained('users'); // Kasir/Pelanggan yg buat order
            $table->foreignId('table_id')->nullable()->constrained('tables'); // Bisa null kalau take away
            $table->string('invoice_code')->unique(); // INV-20240101-001
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', ['pending', 'cooking', 'ready', 'served', 'completed', 'cancelled'])->default('pending');
            $table->text('note')->nullable(); // Catatan pesanan global
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
