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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('category_id')->default(0);
            $table->unsignedInteger('subcategory_id')->default(0);
            $table->string('job_code', 40)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('job_proof')->default(0)->comment('1=No Job Proof, 2=Required Job Proof');
            $table->string('file_name', 255)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('vacancy_available')->default(0);
            $table->decimal('rate', 28, 8)->default(0.00000000);
            $table->decimal('total', 28, 8)->default(0.00000000);
            $table->decimal('amount', 28, 8)->default(0.00000000);
            $table->string('attachment', 255)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: pending, 1: approve, 2: completed, 3: pause, 9: rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
