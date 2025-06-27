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
        Schema::create('price_service', function (Blueprint $table) {
            $table->id();
            // 1→1 con service
            $table->foreignId('service_id')
                  ->constrained('service')
                  ->onDelete('cascade')
                  ->unique();
            $table->decimal('value', 10, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_service');
    }
};
