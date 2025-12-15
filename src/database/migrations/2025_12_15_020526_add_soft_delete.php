<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }

        if (Schema::hasColumn('articles', 'deleted_at')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }

        if (Schema::hasColumn('comments', 'deleted_at')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};
