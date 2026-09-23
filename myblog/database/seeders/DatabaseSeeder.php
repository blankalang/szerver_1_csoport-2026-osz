<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // A WithoutModelEvents trait kikapcsolja az Eloquent modell eseményeket a seeder futása közben, hogy a tesztadatok betöltésekor ne fusson le üzleti logika.
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // A User factory-t használjuk a felhasználók létrehozásához, majd a Post modelben létrehozunk 20 posztot, amelyekhez véletlenszerűen hozzárendeljük a felhasználókat. Ezután 5 kategóriát hozunk létre, és minden kategóriához véletlenszerűen hozzárendelünk 1-5 posztot.

        $users = User::factory(10)->create();
        //Létrehoz egy üres Laravel Collection-t ebbe fogjuk gyűjteni a létrehozott Post objektumokat
        $posts = collect();
        for ($i = 0; $i < 20; $i++){
            //létrehoz egy új Post rekordot, azonnal elmenti az adatbázisba, majd hozzáadja a $posts Collection-höz
            $posts -> add(Post::create([
                // Fakerrel generál egy 3 szavas címet, a true miatt stringként adja vissza (nem tömbként)
                'title' => fake() -> words(3, true),
                //generál bekezdésnyi szöveget
                'content' => fake() -> paragraph(),
                'is_public' => fake() -> boolean(),
                'author_id' => $users -> random() -> id // 1:N
            ]));
        }
        // A Category modelben létrehozunk 5 kategóriát, majd minden kategóriához véletlenszerűen hozzárendelünk 1-5 posztot a pivot táblán keresztül. A sync() metódus biztosítja, hogy a pivot tábla frissüljön a hozzárendelt posztokkal. A pluck('id') metódus segítségével csak a posztok azonosítóit adjuk át a sync() metódusnak.
        for ($i = 0; $i < 5; $i++){
            $c = Category::create([
                //Faker generál egy véletlen szót és hexadecimális színkódot UI címkéhez
                'name' => fake() -> word(),
                'color' => fake() -> hexColor()
            ]);
            // Ez a sor valósítja meg a many-to-many kapcsolatot.
            //$c->posts(): egy belongsToMany kapcsolat, a Category modellben definiált metódus, a pivot táblát használja (pl. category_post)
            //$posts->random(rand(1, 5)): $posts egy Collection (pl. 20 Post). rand(1, 5) → véletlen szám 1 és 5 között. random(n) → kiválaszt n darab véletlen Postot. Eredmény: egy Collection Post objektumokkal
            //->pluck('id'): a kiválasztott Post objektumokból csak az id mezőket szedi ki, Eredmény: [3, 7, 12]

            $c -> posts() -> sync( $posts -> random(rand(1, 5)) -> pluck('id') ); // N:N
        }
    }
}
