<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
        // A PostFactory osztályban a definition() metódusban meghatározzuk a Post modell alapértelmezett állapotát, azaz hogy milyen adatokat generáljon a faker könyvtár segítségével, amikor új bejegyzést hozunk létre. A 'title' mezőbe 3 véletlenszerű szót generálunk, a 'content' mezőbe egy véletlenszerű bekezdést, az 'is_public' mezőbe egy véletlenszerű logikai értéket (true vagy false), az 'author_id' mezőbe pedig egy véletlenszerűen kiválasztott felhasználó azonosítóját (id) rendeljük hozzá, amelyet a User modell inRandomOrder() metódusával választunk ki. A first() metódus segítségével az első találatot kapjuk meg, majd az id mezőt használjuk az author_id mező értékének beállításához, azért, hogy a bejegyzéshez hozzárendeljük a szerzőt véletlenszerűen kiválasztott felhasználó alapján.
            'title' => fake() -> words(3, true),
            'content' => fake() -> paragraph(),
            'is_public' => fake() -> boolean(),
            'author_id' => User::inRandomOrder() -> first() -> id
        ];

    }
}
