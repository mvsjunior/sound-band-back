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
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                    ->after('id')
                    ->nullable()
                    ->constrained('roles');
            }

            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')
                    ->default(true)
                    ->after('password');
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }

            if (Schema::hasColumn('users', 'active')) {
                $table->dropColumn('active');
            }

        });
    }
};
