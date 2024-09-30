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
        Schema::create('forget_verification_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('code');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('phone_number');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forget_verification_codes');
    }
};
