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
        Schema::create('review_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_shop_id')->constrained()->cascadeOnDelete();
            $table->string('key')->nullable();
            $table->string('question')->nullable();
            $table->enum('type', ['single', 'multiple', 'text'])->default('single');
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(true);
            $table->unsignedTinyInteger('max_select')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_questions');
    }
};
