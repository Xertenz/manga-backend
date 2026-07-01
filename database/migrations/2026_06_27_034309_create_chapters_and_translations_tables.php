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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manga_id')->constrained()->onDelete('cascade');

            $table->float('chapter_number');
            $table->timestamps();
        });

        Schema::create('chapter_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('locale')->index();

            $table->string('title')->nullable();
            $table->string('slug');

            $table->timestamps();

            $table->unique(['chapter_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('chapter_translations');
    }
};
