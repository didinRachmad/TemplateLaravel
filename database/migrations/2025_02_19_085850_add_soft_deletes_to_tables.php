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
        $tables = [
            'users',
            'roles',
            'permissions',
            'menus',
            'approval_routes',
            'items',
            'menu_role_permission'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes(); // Menambahkan kolom deleted_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'roles',
            'permissions',
            'menus',
            'approval_routes',
            'items',
            'menu_role_permission'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes(); // Menghapus kolom deleted_at jika rollback
            });
        }
    }
};
