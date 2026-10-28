<?php
//Ez a migrációs fájl a posts tábla módosítására szolgál, hogy hozzáadjuk az image oszlopot. A migrációs fájlban az up() metódusban definiáljuk a módosítást, azaz a posts táblához hozzáadunk egy új oszlopot, amelynek típusa string és nullable (azaz lehet null értékű). A down() metódusban definiáljuk a visszavonást, azaz eltávolítjuk az image oszlopot a posts táblából. Futtatni kell a migrációt a php artisan migrate parancs segítségével, hogy a módosítások érvénybe lépjenek az adatbázisban.
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
        Schema::table('posts', function (Blueprint $table) {
            //
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
                                  $table->dropColumn('image');
        });
    }
};
