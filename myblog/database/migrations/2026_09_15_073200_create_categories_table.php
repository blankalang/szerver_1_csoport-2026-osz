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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //Ez a sor hozzáadja a name oszlopot a categories táblához, a name egy karakterlánc (string) típusú értéket tárol.
            $table->string('name');
            //Ez a sor hozzáadja a color oszlopot a categories táblához, a color egy karakterlánc (string) típusú értéket tárol.
            $table->string('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
