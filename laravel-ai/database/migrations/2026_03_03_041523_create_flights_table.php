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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number');
            $table->string('from_code');
            $table->string('to_code');
            $table->dateTime('departure_at');
            $table->decimal('price', 15, 2);
            $table->integer('total_seats')->default(100);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained();
            $table->string('customer_name');
            $table->string('status')->default('paid');
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
        Schema::dropIfExists('bookings');
    }
};
