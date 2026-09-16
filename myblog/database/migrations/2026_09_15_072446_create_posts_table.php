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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //Ez a sor hozzáadja a title oszlopot a posts táblához, a title egy karakterlánc (string) típusú értéket tárol.
            $table->string('title');
            //Ez a sor hozzáadja a content oszlopot a posts táblához, a content egy karakterlánc (string) típusú értéket tárol.
            $table->string('content');
            //Ez a sor hozzáadja az is_public oszlopot a posts táblához, az is_public egy logikai értéket (boolean) tárol, és alapértelmezés szerint hamis (false) értékre van állítva.
            $table->boolean('is_public')->default(false);
            //Ez a sor hozzáadja az author_id oszlopot a posts táblához, az author_id egy idegen kulcs (foreign key) lesz, és hivatkozik a users tábla id oszlopára.
            $table->foreignId('author_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
