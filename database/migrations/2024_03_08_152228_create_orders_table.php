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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('status')->default(0); // 0 = pending, 1 = completed , 2 = cancelled
            $table->decimal('total' , 20 , 2);
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('address_2')->nullable();
            $table->string('name');
            $table->string('city');
            $table->integer('tax')->default(0);
            $table->integer("shipping")->nullable();
            $table->string('postal_code');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
