<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->boolean('removed_by_a')->default(false);
            $table->boolean('removed_by_b')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropColumn(['removed_by_a', 'removed_by_b']);
        });
    }
};
