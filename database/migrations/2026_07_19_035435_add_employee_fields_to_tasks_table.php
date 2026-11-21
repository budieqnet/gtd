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
        Schema::table('tasks', function (Blueprint $table) {
            // Kolom untuk assignment tugas (atasan -> bawahan)
            $table->unsignedBigInteger('assigned_to')->nullable()->after('id');
            $table->unsignedBigInteger('created_by')->nullable()->after('title'); // setelah title atau kolom lain
            
            // Pastikan ada kolom due_date kalau belum ada
            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->dateTime('due_date')->nullable()->after('description');
            }

            // Foreign keys ke tabel employees
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('set null');
                  
            $table->foreign('created_by')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
            
            // Index untuk performa query
            $table->index(['assigned_to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['created_by']);
            $table->dropIndex(['assigned_to', 'status']);
            
            $table->dropColumn(['assigned_to', 'created_by', 'due_date']);
        });
    }
};
