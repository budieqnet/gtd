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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('email')->unique();
            $table->unsignedBigInteger('superior_id')->nullable();
            $table->string('password_hash');
            $table->rememberToken();
            $table->timestamps();

            // Foreign key untuk self-referencing (atasan -> bawahan)
            $table->foreign('superior_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('set null');
            
            // Index agar query lebih cepat
            $table->index(['position', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
