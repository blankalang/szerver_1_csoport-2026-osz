<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Http\Resources\PostResource;

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
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
