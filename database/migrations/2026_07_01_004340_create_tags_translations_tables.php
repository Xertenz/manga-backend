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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            // نوع الوسم: genre (تصنيف رئيسي مثل أكشن)، theme (موضوع مثل سحر)، format (شكل العمل مثل غلاف ملون)
            $table->enum('type', ['genre', 'theme', 'format'])->default('genre')->index();
            $table->timestamps();
        });

        Schema::create('tag_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('name');
            $table->string('slug');

            $table->timestamps();

            $table->unique(['tag_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_translations');
    }
};
