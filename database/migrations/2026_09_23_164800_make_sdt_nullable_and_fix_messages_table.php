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
        // Cho phép sdt có thể null trong bảng users
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'sdt')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('sdt', 20)->nullable()->change();
            });
        }

        // Đổi tên cột content thành message trong bảng messages nếu chưa có
        if (Schema::hasTable('messages')) {
            if (Schema::hasColumn('messages', 'content') && !Schema::hasColumn('messages', 'message')) {
                Schema::table('messages', function (Blueprint $table) {
                    $table->renameColumn('content', 'message');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('messages') && Schema::hasColumn('messages', 'message') && !Schema::hasColumn('messages', 'content')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->renameColumn('message', 'content');
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'sdt')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('sdt', 20)->nullable(false)->change();
            });
        }
    }
};
