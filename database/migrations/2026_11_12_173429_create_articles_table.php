<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');             // Заголовок новости
            $table->text('description');         // Краткое описание или текст новости
            $table->string('image')->nullable(); // Ссылка на картинку (необязательно)
            $table->date('published_at');        // Дата публикации
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
