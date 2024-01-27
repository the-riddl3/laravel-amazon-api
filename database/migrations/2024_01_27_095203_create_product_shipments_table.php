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
        Schema::create('product_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')
                ->references('id')
                ->on('product_purchases')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('current_state')->default(0);
            $table->text('current_message')->nullable();

            $table->index('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shipments');
    }
};
