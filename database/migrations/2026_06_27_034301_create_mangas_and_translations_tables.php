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
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->enum('status', ['ongoing', 'completed', 'hiatus'])->default('ongoing');

            $table->timestamps();
        });

        Schema::create('manga_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manga_id')->constrained()->onDelete('cascade');

            $table->string('locale')->index();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('slug')->unique();

            $table->timestamps();

            $table->unique(['manga_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangas');
        Schema::dropIfExists('manga_translations');
    }
};
