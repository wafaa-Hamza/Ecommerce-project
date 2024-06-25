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
            $table->timestamps();
            $table->string('name');
            $table->longText('short_description');
            $table->longText('long_description');
            $table->integer('quantity');
            $table->boolean('live');
            $table->string('image');
            $table->string('additional_images');
            $table->dateTime('expires_at')->nullable();
            $table->decimal('price', 15,2);
            $table->decimal('priceAfter', 15,2)->nullable();
            $table->string("sku")->unique();
            $table->json("colors");
            $table->json("sizes");
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
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
