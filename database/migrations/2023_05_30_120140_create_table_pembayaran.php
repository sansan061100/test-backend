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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('payment_number')->unique();
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->integer('payment')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
