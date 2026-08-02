<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cart items now hang off a cart (which may belong to a user or to a guest)
     * instead of being attached to a user directly.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'product_variant_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('cart_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(['cart_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropUnique(['cart_id', 'product_variant_id']);
            $table->dropColumn('cart_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(['user_id', 'product_variant_id']);
        });
    }
};
