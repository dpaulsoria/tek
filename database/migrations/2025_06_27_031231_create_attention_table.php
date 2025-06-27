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
        Schema::create('attention', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('cite_id')
                ->constrained('cite')
                ->onDelete('cascade');
            $table->foreignId('service_id')
                ->constrained('service')
                ->onDelete('cascade');
            $table->decimal('price_service', 10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attention');
    }
};
