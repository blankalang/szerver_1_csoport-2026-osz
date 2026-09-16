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
        Schema::create('category_post', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //Ez a sor hozzáadja a post_id oszlopot a category_post táblához, a post_id egy idegen kulcs (foreign key) lesz, és hivatkozik a posts tábla id oszlopára. A constrained a névkonvenciók alapján kitalűlja a hivatkozott táblát és oszlopot, az onDelete('cascade') pedig azt jelenti, hogy ha a hivatkozott kategória törlésre kerül, akkor a kapcsolódó rekordok a category_post táblában is törlődnek.
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            //Ez a sor hozzáadja a category_id oszlopot a category_post táblához, a category_id egy idegen kulcs (foreign key) lesz, és hivatkozik a categories tábla id oszlopára. A constrained a névkonvenciók alapján kitalűlja a hivatkozott táblát és oszlopot, az onDelete('cascade') pedig azt jelenti, hogy ha a hivatkozott kategória törlésre kerül, akkor a kapcsolódó rekordok a category_post táblában is törlődnek.
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            //Ez a sor hozzáadja az összetett egyedi kulcsot a category_post táblához, a kulcs megakadályozza, hogy ugyanaz az kategória és poszt páros ismételten létrejöjjön. A unique(['category_id', 'post_id']) azt jelenti, hogy a category_id és post_id kombinációja egyedinek kell lennie a táblában.
            $table->unique(['category_id', 'post_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_post');
    }
};
