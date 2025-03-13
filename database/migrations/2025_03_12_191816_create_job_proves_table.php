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
        Schema::create('job_proves', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('job_post_id')->default(0);
            $table->text('detail')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: pending, 1: approved, 2: rejected');
            $table->tinyInteger('notification')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_proves');
    }
};
