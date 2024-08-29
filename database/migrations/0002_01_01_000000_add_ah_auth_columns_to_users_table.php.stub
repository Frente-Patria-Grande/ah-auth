<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ah_access_token', 1200)->nullable();
            $table->string('ah_refresh_token', 1200)->nullable();
            $table->timestamp('ah_access_token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ah_access_token');
            $table->dropColumn('ah_refresh_token');
            $table->dropColumn('ah_access_token_expires_at');
        });
    }
};
