<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
            $table->string('name_ar')->nullable()->after('slug');
            $table->string('name_en')->nullable()->after('name_ar');
            $table->text('description_ar')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description_ar');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('is_best_seller')->default(false)->after('is_featured');
            $table->boolean('is_new_arrival')->default(false)->after('is_best_seller');
        });

        DB::table('products')->orderBy('id')->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update([
                'slug' => $this->uniqueSlug($product->name, $product->id),
                'name_ar' => $product->name,
                'name_en' => $product->name,
                'description_ar' => $product->description,
                'description_en' => $product->description,
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->string('name_ar')->nullable(false)->change();
            $table->string('name_en')->nullable(false)->change();
            $table->unique('slug');
            $table->dropColumn(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->text('description')->nullable()->after('name');
        });

        DB::table('products')->orderBy('id')->each(function ($product) {
            DB::table('products')->where('id', $product->id)->update([
                'name' => $product->name_ar ?: $product->name_en,
                'description' => $product->description_ar ?: $product->description_en,
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'name_ar',
                'name_en',
                'description_ar',
                'description_en',
                'is_featured',
                'is_best_seller',
                'is_new_arrival',
            ]);
        });
    }

    private function uniqueSlug(?string $name, int $id): string
    {
        $base = Str::slug((string) $name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (DB::table('products')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
};
