<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostController extends Controller
{
    //Kiszerveztük ide a web.php-ból a logikát, hogy a web.php fájlban ne legyen túl sok kód. A gyökérútvonalra érkező GET kérést a PostController index() metódusa kezeli.  Az index() metódusban lekérjük az összes bejegyzést a Post model segítségével, majd visszaadjuk a posts.index Blade nézetet, és átadjuk neki a lekérdezett bejegyzéseket.
 public function index()
    {
        //  $posts = Post::all();
        // A with('author') metódus segítségével előre betöltjük a kapcsolódó author modellt, így elkerüljük az N+1 problémát. Ez azt jelenti, hogy amikor lekérjük az összes posztot, az Eloquent automatikusan betölti a kapcsolódó szerzőket is egyetlen lekérdezéssel, így csökkentve az adatbázis lekérdezések számát. LAravel debugburral is meg lehet nézni, hogy hány lekérdezés történik. Ha nincs a with('author'), akkor minden poszthoz külön lekérdezés történne a szerző nevének lekérésére, ami jelentősen megnövelné a lekérdezések számát.
        $posts = Post::with('author')->get();
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post){
        return view('posts.show', ['post' => $post ]);
    }

    public function create(){
        return view('posts.create', [
            'users' => User::all(),
            'categories' => Category::all()
        ]);
    }

   public function store(Request $request){
    // Innen jönnek az automatikus hibaüzenetek, ha a validáció sikertelen volt. A validációs szabályokat a $request->validate() metódusban adhatjuk meg. Ha a validáció sikertelen, akkor a felhasználót visszairányítjuk az előző oldalra, és a hibákat a session-ben tároljuk. A hibák elérhetők a Blade nézetben az @error direktívával.
        $validated = $request -> validate([
            'title' => 'required|string',
            'content' => 'required|string|min:10',
            // Az 'author_id' mezőnek kötelezően egy létező felhasználó ID-jét kell tartalmaznia. Az 'exists:users,id' szabály biztosítja, hogy a megadott ID valóban létezik a users táblában.
            'author_id' => 'required|integer|exists:users,id',
            'categories' => 'array',
            // A 'categories.*' mező minden elemének egy létező kategória ID-jét kell tartalmaznia. Az 'exists:categories,id' szabály biztosítja, hogy a megadott ID valóban létezik a categories táblában. A 'distinct' szabály biztosítja, hogy ne legyenek duplikált kategória ID-k a tömbben.
            'categories.*' => 'integer|distinct|exists:categories,id'
        ], [
            // Ez egyedi hibaüzenet a content mezőre vonatkozik, ha a validáció sikertelen volt. A validációs szabályokban megadott üzeneteket felülírhatjuk, ha egyedi üzenetet szeretnénk megjeleníteni a felhasználónak.
            'content.min' => 'A tartalom legalább 10 karakter kell legyen!'
        ]);
        // A $request->has('is_public') metódus segítségével ellenőrizzük, hogy a felhasználó bejelölte-e a publikus jelölőnégyzetet.
        $validated['is_public'] = $request -> has('is_public');
        // Itt a fillable mezőket töltjük fel a validált adatokkal mass assignment segítségével. A Post modelben a $fillable tulajdonságban megadott mezők lesznek kitöltve a $validated tömbből. Ez biztosítja, hogy csak a megadott mezők legyenek kitöltve, és elkerüljük a tömeges hozzárendelés (mass assignment) problémáját.
        $post = Post::create($validated);
        // A sync() metódus segítségével szinkronizáljuk a poszt és a kategóriák közötti kapcsolatot. A $validated['categories'] ?? [] kifejezés azt jelenti, hogy ha a validált adatok között nincs 'categories' kulcs, akkor egy üres tömböt adunk át a sync() metódusnak. Ez biztosítja, hogy ha a felhasználó nem választott kategóriát, akkor a poszthoz ne legyenek társítva kategóriák.
        $post -> categories() -> sync($validated['categories'] ?? []);
        // A redirect() metódus segítségével átirányítjuk a felhasználót a posts.index route-ra, ami a bejegyzések listáját jeleníti meg. A route() metódus segítségével generáljuk a megfelelő URL-t a posts.index route-hoz.
        return redirect() -> route('posts.index');
    }


}
