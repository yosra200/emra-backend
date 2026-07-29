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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
            $table->string('name_ar')->nullable()->after('slug');
            $table->string('name_en')->nullable()->after('name_ar');
        });

        DB::table('categories')->orderBy('id')->each(function ($category) {
            DB::table('categories')->where('id', $category->id)->update([
                'slug' => $this->uniqueSlug($category->name, $category->id),
                'name_ar' => $category->name,
                'name_en' => $category->name,
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->string('name_ar')->nullable(false)->change();
            $table->string('name_en')->nullable(false)->change();
            $table->unique('slug');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        DB::table('categories')->orderBy('id')->each(function ($category) {
            DB::table('categories')->where('id', $category->id)->update([
                'name' => $category->name_ar ?: $category->name_en,
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'name_ar', 'name_en']);
        });
    }

    private function uniqueSlug(?string $name, int $id): string
    {
        $base = Str::slug((string) $name) ?: 'category';
        $slug = $base;
        $suffix = 2;

        while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
};
