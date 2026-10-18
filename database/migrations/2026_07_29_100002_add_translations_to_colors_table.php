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
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_ar');
        });

        DB::table('colors')->orderBy('id')->each(function ($color) {
            DB::table('colors')->where('id', $color->id)->update([
                'name_ar' => $color->name,
                'name_en' => $color->name,
            ]);
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->string('name_ar')->nullable(false)->change();
            $table->string('name_en')->nullable(false)->change();
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        DB::table('colors')->orderBy('id')->each(function ($color) {
            DB::table('colors')->where('id', $color->id)->update([
                'name' => $color->name_ar ?: $color->name_en,
            ]);
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropColumn(['name_ar', 'name_en']);
        });
    }
};
