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
        Schema::create('ticketts', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->unsignedBigInteger('reservation_id');
            $table->timestamp('purchase_time');
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            $table->json('user_info');
            $table->json('passenger_info')->nullable();
            $table->json('sans_info');
            $table->integer('ticket_count');
            $table->decimal('total_amount', 10, 2)->nullable();
           // $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('final_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketts');
    }
};
