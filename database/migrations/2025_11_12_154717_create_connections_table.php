<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();

            // Explicitly define foreign keys for Laravel 12
            $table->unsignedBigInteger('user_a_id');
            $table->unsignedBigInteger('user_b_id');

            $table->enum('status', [
                'requested',     // Request sent, awaiting response
                'active',        // Connection approved
                'refused',       // Connection request refused
                'deactivated'    // Connection later removed
            ])->default('requested');

            $table->timestamps();

            // Unique pairing constraint
            $table->unique(['user_a_id', 'user_b_id']);

            // Explicit foreign key constraints
            $table->foreign('user_a_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('user_b_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
