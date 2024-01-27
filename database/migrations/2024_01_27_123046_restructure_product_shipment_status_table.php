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
        Schema::table('product_shipment_statuses', function(Blueprint $table) {
            $table->dropConstrainedForeignId('purchase_id');
            $table->foreignId('shipment_id')
                ->references('id')
                ->on('product_shipments')
                ->onDelete('cascade');

            $table->index('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_shipment_statuses', function(Blueprint $table) {
            $table->dropConstrainedForeignId('shipment_id');
            $table->foreignId('purchase_id')
                ->references('id')
                ->on('product_purchases')
                ->onDelete('cascade');

            $table->index('purchase_id');
        });
    }
};
