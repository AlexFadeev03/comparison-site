<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('image', 255)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->float('rating')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->text('features')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
