<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostStoreOrUpdateRequest;
use App\Http\Resources\CategoryResource;

//ApiController fájl feladata, hogy a postokkal és a bejelentkezéssel kapcsolatos API végpontokat kiszolgálja, JSON válaszokat adjon vissza, és kezelje az autentikációt, a validációt és a hibakezelést.
class ApiController extends Controller
{
    // A login metódus a bejelentkezési folyamatot kezeli. A metódus a Request objektumot várja paraméterként, amely tartalmazza a felhasználó által megadott adatokat (email és jelszó). A metódus először validálja az adatokat, majd megpróbálja autentikálni a felhasználót az Auth::attempt() metódussal. Ha az autentikáció sikeres, létrehoz egy új API tokent a felhasználó számára, és visszaadja azt JSON formátumban. Ha az autentikáció sikertelen, egy 401-es hibakódot ad vissza egy hibaüzenettel.
    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|string|email",
            "password" => "required|string"
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();
            $token = $user->createToken('loginToken');
            return response()->json(["token" => $token->plainTextToken], 201);
        } else {
            return response()->json(["message" => "Login failed."], 401);
        }
    }
    /**
     * Display a listing of the resource.
     */
    //Az index metódus a posztok listáját adja vissza JSON formátumban. A metódus lekéri az összes posztot az adatbázisból a Post::all() metódussal, majd a PostResource::collection() metódussal alakítja át azokat JSON formátumra. A PostResource osztály a posztok adatait formázza és alakítja át a kívánt struktúrába.
    public function index()
    {
        $posts = Post::all();
        //return response() -> json($posts);
        return PostResource::collection($posts);
    }
    // A show() egy adott posztot kér le az azonosítója alapján.
    // Először ellenőrzi, hogy az azonosító egész szám-e.
    // Hibás azonosító esetén a Laravel 422-es választ ad.
    // A findOrFail() lekéri a posztot, vagy kivételt dob,
    // amelyből a Laravel 404-es HTTP-választ készít.
    // A PostResource a poszt adatait JSON-válasszá alakítja.

    public function show(string $post)
    {
        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        )->validate();
        $post = Post::findOrFail($post);
        return new PostResource($post);
    }

    /**
     * Store a newly created resource in storage.
     */
    // A store() metódus új posztot hoz létre az adatbázisban.
    // A PostStoreOrUpdateRequest automatikusan validálja
    // a beérkező adatokat a metódus végrehajtása előtt.
    // A metódus lekéri a validált adatokat, beállítja
    // az is_public értékét és a bejelentkezett felhasználó
    // azonosítóját (author_id), majd létrehozza a posztot a Post::create() metódussal.
    // Végül a PostResource segítségével visszaadja azt JSON formátumban a PostResource segítségével.

    public function store(PostStoreOrUpdateRequest $request)
    {
        $validated = $request->validated();
        $validated['is_public'] = $request->boolean('is_public');
        $validated['author_id'] = $request->user()->id;
        $post = Post::create($validated);
        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     */

    //Az update() metódus egy meglévő posztot frissít az adatbázisban. A metódus a Request objektumot és a poszt azonosítóját várja paraméterként. Először validálja az azonosítót, majd lekéri a posztot a Post::findOrFail() metódussal. Ezután ellenőrzi, hogy a bejelentkezett felhasználó admin-e, vagy ő a poszt szerzője. Ha nem, 403-as HTTP-státuszkódú JSON-választ ad vissza. Ha a felhasználó jogosult a frissítésre, validálja a beérkező adatokat (title, content, is_public, author_id), majd frissíti a posztot a $post->update() metódussal. Végül visszaadja a frissített posztot JSON formátumban a PostResource segítségével.
    public function update(Request $request, string $post)
    {
        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        )->validate();
        $post = Post::findOrFail($post);
        if (!$request->user()->is_admin && $request->user()->id !== $post->author_id)
            return response()->json(["message" => "You cannot do this."], 403);
        $validated = $request->validate([
            "title" => "string|min:10",
            "content" => "string|max:999",
            "is_public" => "boolean",
            'author_id' => "integer|exists:users,id"
        ]);
        $post->update($validated);
        return new PostResource($post);
    }
    //Az indexCategories() metódus egy adott poszthoz tartozó kategóriákat adja vissza JSON formátumban. A metódus a poszt azonosítóját várja paraméterként. Először validálja az azonosítót, majd lekéri a posztot a Post::findOrFail() metódussal. Végül visszaadja a poszthoz tartozó kategóriákat a CategoryResource::collection() metódus segítségével, amely a kategóriák adatait formázza és alakítja át a kívánt struktúrába.
    public function indexCategories(string $post)
    {
        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        )->validate();
        $post = Post::findOrFail($post);
        return CategoryResource::collection($post->categories);
    }
    //Az indexWithCategories() metódus az összes posztot adja vissza a hozzájuk tartozó kategóriákkal együtt JSON formátumban. A metódus lekéri az összes posztot az adatbázisból a Post::with('categories')->get() metódussal, amely előre betölti a posztokhoz tartozó kategóriákat (eager loading). Végül a PostResource::collection() metódus segítségével alakítja át a posztokat és a kategóriákat JSON formátumra.
    public function indexWithCategories()
    {
        $posts = Post::with('categories')->get();
        return PostResource::collection($posts);
    }

    //Az updateCategories() metódus egy adott poszthoz tartozó kategóriákat frissíti. A metódus a Request objektumot és a poszt azonosítóját várja paraméterként. Először validálja az azonosítót, majd lekéri a posztot a Post::findOrFail() metódussal. Ezután validálja a beérkező adatokat, amelyek tartalmazhatnak "add" és "remove" tömböket a kategóriák azonosítóival. A metódus hozzáadja az új kategóriákat a poszthoz a syncWithoutDetaching() metódussal, és eltávolítja a megadott kategóriákat a detach() metódussal. Végül visszaad egy tömböt, amely tartalmazza az újonnan hozzáadott kategóriákat, azokat, amelyek már hozzá voltak adva, az eltávolított kategóriákat és azokat, amelyek már el lettek távolítva.
    public function updateCategories(Request $request, string $post)
    {
        validator(
            ['post' => $post],
            ['post' => 'required|integer']
        )->validate();
        $post = Post::findOrFail($post);
        //A validated tömbben az "add" kulcs tartalmazza az újonnan hozzáadni kívánt kategóriák azonosítóit, a "remove" kulcs tartalmazza az eltávolítani kívánt kategóriák azonosítóit. A validációs szabályok biztosítják, hogy mindkét kulcs egy tömb legyen, amely egész számokat tartalmaz, amelyek léteznek a categories táblában, és hogy ne legyenek átfedések az "add" és "remove" tömbök között.
        $validated = $request->validate([
            "add" => "array",
            "remove" => "array",
            "add.*" => "integer|distinct|exists:categories,id|not_in:" . implode(",", $request->input('remove') ?? []),
            "remove.*" => "integer|distinct|exists:categories,id|not_in:" . implode(",", $request->input('add') ?? []),
        ]);
        //A $validated["add"] ??= []; és a $validated["remove"] ??= []; sorok biztosítják, hogy ha az "add" vagy "remove" kulcs nem szerepel a beérkező kérésben, akkor azok üres tömbként legyenek inicializálva. Ez megakadályozza a hibákat a későbbi műveletek során, amikor a kategóriák hozzáadását vagy eltávolítását végezzük.
        $validated["add"] ??= [];
        $validated["remove"] ??= [];
        $start = $post->categories->pluck('id')->toArray();
        //Az attach() metódus hozzáadja a megadott kategóriákat a poszthoz, de ha egy kategória már hozzá van rendelve, akkor hibát dob. A syncWithoutDetaching() metódus hozzáadja a megadott kategóriákat a poszthoz, de nem távolítja el a már meglévő kategóriákat. A detach() metódus eltávolítja a megadott kategóriákat a poszthoz rendelt kategóriák közül, de ha egy kategória nincs hozzárendelve, akkor nem történik semmi.A syncWithoutDetaching() metódus használata biztosítja, hogy a már meglévő kategóriák ne kerüljenek eltávolításra, miközben az új kategóriák hozzáadódnak. ezzel megoldottuk a vak hozzáadás és eltávolítás problémáját.
        // $post->categories()->attach($validated["add"]);
        $post->categories()->syncWithoutDetaching($validated["add"]);
        $post->categories()->detach($validated["remove"]);
        // return CategoryResource::collection($post -> categories);
        return [
            //A visszaadott tömbben az "added" kulcs tartalmazza az újonnan hozzáadott kategóriák azonosítóit, a "was already added" kulcs tartalmazza azokat a kategóriákat, amelyek már hozzá voltak rendelve a poszthoz, a "removed" kulcs tartalmazza az eltávolított kategóriák azonosítóit, és a "was already removed" kulcs tartalmazza azokat a kategóriákat, amelyek már el lettek távolítva a poszthoz rendelt kategóriák közül.
            "added" => array_diff($validated["add"], $start),
            "was already added" => array_values(array_intersect($start, $validated["add"])),
            "removed" => array_values(array_intersect($start, $validated["remove"])),
            "was already removed" => array_diff($validated["remove"], $start)
        ];
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
