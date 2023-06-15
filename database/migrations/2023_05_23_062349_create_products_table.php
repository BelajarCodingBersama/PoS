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
            $table->string('name')->unique();
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('amount');
            $table->unsignedBigInteger('product_type_id');
            $table->unsignedBigInteger('file_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_type_id')
                ->references('id')
                ->on('product_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
