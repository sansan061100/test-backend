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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('transaction_number')->unique();
            $table->foreignId('marketing_id')->constrained('marketting')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->integer('cargo_fee')->unsigned();
            $table->integer('total_balance')->unsigned();
            $table->integer('grand_total')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
