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
        Schema::table('unit_conversions', function (Blueprint $table) {
            // Add batch_id column only if it doesn't exist
            if (!Schema::hasColumn('unit_conversions', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('product_id');
                $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_conversions', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
    }
};
