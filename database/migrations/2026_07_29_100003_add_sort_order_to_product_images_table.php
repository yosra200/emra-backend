<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('image');
        });

        // Keep the existing order of each product's images instead of leaving every row at 0.
        DB::table('product_images')
            ->select('product_id')
            ->distinct()
            ->orderBy('product_id')
            ->pluck('product_id')
            ->each(function ($productId) {
                DB::table('product_images')
                    ->where('product_id', $productId)
                    ->orderBy('id')
                    ->pluck('id')
                    ->each(function ($id, $index) {
                        DB::table('product_images')->where('id', $id)->update(['sort_order' => $index + 1]);
                    });
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
