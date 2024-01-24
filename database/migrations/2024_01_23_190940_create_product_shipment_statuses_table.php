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
        Schema::create('product_shipment_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('state');
            $table->string('message')->nullable();
            $table->datetime('time');
            $table->foreignId('purchase_id')
                ->references('id')
                ->on('product_purchases')
                ->onDelete('cascade');

            // for better performance when querying by purchase
            $table->index('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shipment_statuses');
    }
};
