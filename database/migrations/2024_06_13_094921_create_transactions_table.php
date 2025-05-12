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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->unsignedBigInteger('detail_id')->index()->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('field_id')->index()->nullable();
            $table->unsignedBigInteger('member_id')->index()->nullable();
            $table->string('day', 20)->nullable();
            $table->string('date', 100)->nullable();
            $table->string('time', 11)->nullable();
            $table->integer('hour')->nullable();
            $table->integer('price')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
