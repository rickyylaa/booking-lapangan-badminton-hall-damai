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
        Schema::create('transaction_cancels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index()->nullable();
            $table->unsignedBigInteger('detail_id')->index()->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('field_id')->index()->nullable();
            $table->string('bank_name', 11)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('account_number', 13)->nullable();
            $table->integer('amount');
            $table->string('proof');
            $table->text('reason');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_cancels');
    }
};
