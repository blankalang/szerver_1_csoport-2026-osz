<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostStoreOrUpdateRequest;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class PostController extends Controller
{
    //Kiszerveztük ide a web.php-ból a logikát, hogy a web.php fájlban ne legyen túl sok kód. A gyökérútvonalra érkező GET kérést a PostController index() metódusa kezeli.  Az index() metódusban lekérjük az összes bejegyzést a Post model segítségével, majd visszaadjuk a posts.index Blade nézetet, és átadjuk neki a lekérdezett bejegyzéseket.
 public function index()
    {
        //  $posts = Post::all();
        // A with('author') metódus segítségével előre betöltjük a kapcsolódó author modellt, így elkerüljük az N+1 problémát. Ez azt jelenti, hogy amikor lekérjük az összes posztot, az Eloquent automatikusan betölti a kapcsolódó szerzőket is egyetlen lekérdezéssel, így csökkentve az adatbázis lekérdezések számát. LAravel debugburral is meg lehet nézni, hogy hány lekérdezés történik. Ha nincs a with('author'), akkor minden poszthoz külön lekérdezés történne a szerző nevének lekérésére, ami jelentősen megnövelné a lekérdezések számát.
        //A paginate(10) metódus segítségével az összes bejegyzést 10-esével lapozzuk, így a Blade nézetben a $posts->links() metódus segítségével megjeleníthetjük a lapozó gombokat. A paginate() metódus automatikusan kezeli az aktuális oldalt a query string alapján, így a felhasználó könnyen navigálhat az oldalak között.
        $posts = Post::with('author')->paginate(10);
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post){
        return view('posts.show', ['post' => $post ]);
    }

    public function create(){
        //A Gate::authorize() metódus segítségével ellenőrizzük, hogy a felhasználónak van-e jogosultsága a poszt létrehozására. Ha nincs, akkor a Laravel automatikusan 403-as hibát dob. A 'create' az engedély neve, amit a PostPolicy osztályban definiáltunk, és a Post::class a modell osztálya, amire az engedély vonatkozik.
        Gate::authorize('create', Post::class);
        return view('posts.create', [
            'users' => User::all(),
            'categories' => Category::all()
        ]);
    }

    //Másoltuk a create-t, és módosítottuk. A Post $post paramétert a route paraméterből kapjuk, és a route model binding segítségével automatikusan lekérjük a megfelelő posztot az adatbázisból. A $post paraméter típusa Post, ami azt jelenti, hogy a route paraméternek egy Post modell példányának kell lennie. Ha a route paraméter nem található az adatbázisban, akkor a Laravel automatikusan 404-es hibát dob.   A 'post'=>$post kulcs-érték pár segítségével átadjuk a poszt objektumot a Blade nézetnek, így a nézetben elérhetjük a poszt adatait. A 'users' => User::all() és 'categories' => Category::all() kulcs-érték párok segítségével átadjuk az összes felhasználót és kategóriát a Blade nézetnek, így a nézetben elérhetjük az összes felhasználót és kategóriát.
     public function edit(Post $post){
        //A Gate::authorize() metódus segítségével ellenőrizzük, hogy a felhasználónak van-e jogosultsága a poszt frissítésére. Ha nincs, akkor a Laravel automatikusan 403-as hibát dob. A 'update' az engedély neve, amit a PostPolicy osztályban definiáltunk, és a $post a poszt objektum, amire az engedély vonatkozik.
     Gate::authorize('update', $post);
        return view('posts.edit', [
            'users' => User::all(),
            'categories' => Category::all(),
            'post'=>$post

        ]);
    }

    //A PostStoreOrUpdateRequest osztályt használjuk  a korábbi PostController helyett, hogy a validációs logikát kiszervezzük egy külön osztályba. A PostStoreOrUpdateRequest osztály a FormRequest osztályból származik, és tartalmazza a validációs szabályokat és az engedélyezési logikát.
 public function store(PostStoreOrUpdateRequest $request)
    {
        //A Gate::authorize() metódus segítségével ellenőrizzük, hogy a felhasználónak van-e jogosultsága a poszt létrehozására. Ha nincs, akkor a Laravel automatikusan 403-as hibát dob. A 'create' az engedély neve, amit a PostPolicy osztályban definiáltunk, és a Post::class a modell osztálya, amire az engedély vonatkozik.
        Gate::authorize('create', Post::class);
     $validated = $request->validated();
        $validated['is_public'] = $request->has('is_public');
        // A Auth::user() metódus segítségével lekérjük a jelenleg bejelentkezett felhasználót, majd az id tulajdonságát hozzárendeljük a $validated tömbhöz az 'author_id' kulcs alatt. Ez biztosítja, hogy a létrehozott poszt a bejelentkezett felhasználóhoz legyen társítva. A Auth::id() metódus rövidebb alternatívája ennek, ami közvetlenül visszaadja a bejelentkezett felhasználó azonosítóját.
          $validated['author_id'] = Auth::user() -> id;  // Auth::id()
        // Itt a fillable mezőket töltjük fel a validált adatokkal mass assignment segítségével. A Post modelben a $fillable tulajdonságban megadott mezők lesznek kitöltve a $validated tömbből. Ez biztosítja, hogy csak a megadott mezők legyenek kitöltve, és elkerüljük a tömeges hozzárendelés (mass assignment) problémáját.
// Az alábbi kód segítségével ellenőrizzük, hogy a request tartalmaz-e fájlt az 'image_file' kulcs alatt. Ha igen, akkor a fájlt feltöltjük a 'public' diskre az "images/" mappába, és a fájl nevét (UUID + kiterjesztés) hozzárendeljük a $validated tömbhöz az 'image' kulcs alatt. Ez biztosítja, hogy a létrehozott poszthoz társítva legyen a feltöltött kép. Az uuid() metódus segítségével egy egyedi azonosítót generálunk a fájl nevéhez, így elkerüljük az ütközéseket a fájlnevek között. A getClientOriginalExtension() metódus segítségével lekérjük a feltöltött fájl eredeti kiterjesztését, így megőrizzük a fájl típusát.
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = Str::uuid() . "." . $file->getClientOriginalExtension();
            Storage::disk('public')->put("images/" . $fileName, $file->getContent());
            $validated['image'] = $fileName;
        }
        $post = Post::create($validated);
        // A sync() metódus segítségével szinkronizáljuk a poszt és a kategóriák közötti kapcsolatot. A $validated['categories'] ?? [] kifejezés azt jelenti, hogy ha a validált adatok között nincs 'categories' kulcs, akkor egy üres tömböt adunk át a sync() metódusnak. Ez biztosítja, hogy ha a felhasználó nem választott kategóriát, akkor a poszthoz ne legyenek társítva kategóriák.
        $post->categories()->sync($validated['categories'] ?? []);
        // A Session::flash() metódus segítségével egy egyszeri üzenetet tárolunk a session-ben, ami a következő kérésnél elérhető lesz. Ez lehetővé teszi, hogy a felhasználónak visszajelzést adjunk a poszt létrehozásáról. A 'post-created' kulcsot használjuk az üzenet azonosítására, és a $post->title értékét tároljuk, ami a létrehozott poszt címét tartalmazza. Frissítés után a session automatikusan törlődik, így az üzenet csak egyszer jelenik meg a felhasználónak.
        Session::flash('post-created', $post -> title);
        // A redirect() metódus segítségével átirányítjuk a felhasználót a posts.index route-ra, ami a bejegyzések listáját jeleníti meg. A route() metódus segítségével generáljuk a megfelelő URL-t a posts.index route-hoz.
        return redirect()->route('posts.index');
    }

   public function update(PostStoreOrUpdateRequest $request,Post $post){
    //A Gate::authorize() metódus segítségével ellenőrizzük, hogy a felhasználónak van-e jogosultsága a poszt frissítésére. Ha nincs, akkor a Laravel automatikusan 403-as hibát dob. A 'update' az engedély neve, amit a PostPolicy osztályban definiáltunk, és a $post a poszt objektum, amire az engedély vonatkozik.
    Gate::authorize('update', $post);
//A Post $post paramétert a route paraméterből kapjuk, és a route model binding segítségével automatikusan lekérjük a megfelelő posztot az adatbázisból. A $post paraméter típusa Post, ami azt jelenti, hogy a route paraméternek egy Post modell példányának kell lennie. Ha a route paraméter nem található az adatbázisban, akkor a Laravel automatikusan 404-es hibát dob.
     $validated = $request->validated();
        $validated['is_public'] = $request -> has('is_public');
        //$post = Post::update($validated) itt nem lenne jó. Ehelyett a $post->update($validated) példány metódust használjuk, ami csak a megadott posztot frissíti az adatbázisban a validált adatokkal.
        $post->update($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        // A Session::flash() metódus segítségével egy egyszeri üzenetet tárolunk a session-ben, ami a következő kérésnél elérhető lesz. Ez lehetővé teszi, hogy a felhasználónak visszajelzést adjunk a poszt frissítéséről. A 'post-updated' kulcsot használjuk az üzenet azonosítására, és a $post->title értékét tároljuk, ami a frissített poszt címét tartalmazza. Frissítés után a session automatikusan törlődik, így az üzenet csak egyszer jelenik meg a felhasználónak.
        Session::flash('post-updated', $post -> title);
        return redirect() -> route('posts.index');
    }
public function destroy(Post $post){
    //A Gate::authorize() metódus segítségével ellenőrizzük, hogy a felhasználónak van-e jogosultsága a poszt törlésére. Ha nincs, akkor a Laravel automatikusan 403-as hibát dob. A 'delete' az engedély neve, amit a PostPolicy osztályban definiáltunk, és a $post a poszt objektum, amire az engedély vonatkozik.
        Gate::authorize('delete', $post);
        $title = $post -> title;
        $post -> delete(); // Post::destroy($post -> id);
        // A Session::flash() metódus segítségével egy egyszeri üzenetet tárolunk a session-ben, ami a következő kérésnél elérhető lesz. Ez lehetővé teszi, hogy a felhasználónak visszajelzést adjunk a poszt törléséről. A 'post-deleted' kulcsot használjuk az üzenet azonosítására, és a $title értékét tároljuk, ami a törölt poszt címét tartalmazza. Frissítés után a session automatikusan törlődik, így az üzenet csak egyszer jelenik meg a felhasználónak.
        Session::flash('post-deleted', $title);
        return redirect() -> route('posts.index');
    }


}
