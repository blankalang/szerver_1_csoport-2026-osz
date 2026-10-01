<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //A DataBaseSeeder osztályban a run() metódusban meghívjuk a User, Post és Category modellek factory-jait, hogy létrehozzunk 10 felhasználót, egy admin felhasználót, 20 bejegyzést és 5 kategóriát. A kategóriákhoz véletlenszerűen hozzárendeljük a bejegyzéseket, így minden kategória 1-5 bejegyzést tartalmazhat. A Factorykbe szerveztük ki a létrehozandó adatok generálását, így a seeder osztályban csak a létrehozás logikáját kell megadnunk.
User::factory(10)->create();
// Létrehozunk egy admin felhasználót az 'admin@szerveroldali.hu' e-mail címmel és az 'is_admin' mezőt true-ra állítjuk, hogy jelezzük, hogy ez a felhasználó adminisztrátor.
        User::factory()->create(['email' => 'admin@szerveroldali.hu', 'is_admin' => true]);
        // Létrehozunk 20 bejegyzést a Post model factory-jával,
        $posts = Post::factory(20)->create();
        // Létrehozunk 5 kategóriát a Category model factory-jával, majd minden kategóriához véletlenszerűen hozzárendeljük a bejegyzéseket. A sync() metódus segítségével a kategóriák és a bejegyzések közötti kapcsolatot állítjuk be, ahol a random() metódus segítségével kiválasztunk 1-5 véletlenszerű bejegyzést, amelyeket hozzárendelünk az adott kategóriához. A pluck('id') metódus segítségével csak a bejegyzések azonosítóit adjuk át a sync() metódusnak, így a kategóriák és a bejegyzések közötti kapcsolatot az adatbázisban tároljuk.
        Category::factory(5)->create()->each(function($c) use ($posts) {
            $c -> posts() -> sync( $posts -> random(rand(1, 5)) -> pluck('id') );
        });

    }
}
