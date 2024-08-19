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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('price');
            $table->integer('time');
            $table->integer('Discount percentage')->nullable();
            $table->integer('age_limited');
            $table->integer('total');
            $table->integer('pending');
            $table->enum('off_suggestion', ['yes', 'no'])->default('no');
            $table->text('description');
            $table->time('started_at');
            $table->time('ended_at');
            $table->text('tip');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
