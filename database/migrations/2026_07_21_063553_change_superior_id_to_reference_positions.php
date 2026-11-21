<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['superior_id']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('superior_id')->nullable()->change();
            $table->foreign('superior_id')
                  ->references('id')
                  ->on('positions')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['superior_id']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('superior_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('set null');
        });
    }
};
