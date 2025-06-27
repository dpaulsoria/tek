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
        Schema::create('cite', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('cliente_id')
                ->constrained('person')
                ->onDelete('cascade');
            $table->decimal('amount_attention', 10, 2);
            $table->time('time_arrival');
            $table->decimal('total_service', 10,2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cite');
    }
};
