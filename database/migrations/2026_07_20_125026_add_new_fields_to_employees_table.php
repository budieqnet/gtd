<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nip')->unique()->nullable()->after('id');
            $table->string('front_title')->nullable()->after('name');
            $table->string('back_title')->nullable()->after('front_title');
            $table->string('nik')->unique()->nullable()->after('back_title');
            $table->string('religion')->nullable()->after('nik');
            $table->string('marital_status')->nullable()->after('religion');
            $table->string('education')->nullable()->after('marital_status');
            $table->text('address')->nullable()->after('education');
            $table->string('birth_place')->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('gender')->nullable()->after('birth_date');
            $table->string('group_rank')->nullable()->after('gender');
            $table->date('group_start_date')->nullable()->after('group_rank');
            $table->date('position_start_date')->nullable()->after('group_start_date');
            $table->string('phone')->nullable()->after('position_start_date');
            $table->string('photo')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'nip', 'front_title', 'back_title', 'nik', 'religion',
                'marital_status', 'education', 'address', 'birth_place',
                'birth_date', 'gender', 'group_rank', 'group_start_date',
                'position_start_date', 'phone', 'photo',
            ]);
        });
    }
};
