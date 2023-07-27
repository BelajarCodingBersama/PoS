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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->decimal('basic_salary', 16, 2);
            $table->decimal('allowances', 16, 2);
            $table->decimal('tax', 16, 2);
            $table->date('payment_date')->nullable();
            $table->decimal('net_pay', 16, 2);
            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
