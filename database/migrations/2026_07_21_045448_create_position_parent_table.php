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
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::create('position_parent', function (Blueprint $table) {
            $table->foreignId('position_id')->constrained('positions')->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('positions')->cascadeOnDelete();
            $table->primary(['position_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_parent');

        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('level')->constrained('positions')->nullOnDelete();
        });
    }
};
