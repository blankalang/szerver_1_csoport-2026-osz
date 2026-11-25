<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

//Az api.php fájl feladata, hogy az alkalmazás API végpontjait (route-jait) definiálja, és azokat a megfelelő controller metódusokhoz rendelje.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Az api.php fájlban definiált útvonalak a bootstrap/app.php fájlban beállított 'apiPrefix' érték alapján lesznek elérhetők. Ha az apiPrefix 'api', akkor az összes API végpont a /api/ útvonal alatt lesz elérhető.
//A Route::post('/login', [ApiController::class, 'login']); sor azt jelenti, hogy amikor egy POST kérést küldenek a /api/login útvonalra, az ApiController osztály login metódusa fogja kezelni azt. A Route::get('/posts', [ApiController::class, 'index']); sor azt jelenti, hogy amikor egy GET kérést küldenek a /api/posts útvonalra, az ApiController osztály index metódusa fogja kezelni azt. A Route::get('/posts/{post}', [ApiController::class, 'show']); sor azt jelenti, hogy amikor egy GET kérést küldenek a /api/posts/{post} útvonalra (ahol {post} egy változó, amely a poszt azonosítóját jelöli), az ApiController osztály show metódusa fogja kezelni azt.
Route::post('/login', [ApiController::class, 'login']);
Route::get('/posts', [ApiController::class, 'index']);
// A Route::get('/posts/categories', [ApiController::class, 'indexWithCategories']); sor azt jelenti, hogy amikor egy GET kérést küldenek a /api/posts/categories útvonalra, az ApiController osztály indexWithCategories metódusa fogja kezelni azt. Ez a metódus  visszaadja az összes posztot a hozzájuk tartozó kategóriákkal együtt.
Route::get('/posts/categories', [ApiController::class, 'indexWithCategories']);
// A Route::get('/posts/{post}', [ApiController::class, 'show']); sor azt jelenti, hogy amikor egy GET kérést küldenek a /api/posts/{post} útvonalra (ahol {post} egy változó, amely a poszt azonosítóját jelöli), az ApiController osztály show metódusa fogja kezelni azt. Ez a metódus visszaadja a megadott azonosítóval rendelkező posztot.
Route::get('/posts/{post}', [ApiController::class, 'show']);

// A Route::post('/posts', [ApiController::class, 'store'])->middleware('auth:sanctum'); sor azt jelenti, hogy amikor egy POST kérést küldenek a /api/posts útvonalra, az ApiController osztály store metódusa fogja kezelni azt. Ez a metódus létrehoz egy új posztot az adatbázisban. A middleware('auth:sanctum') rész azt jelenti, hogy csak hitelesített felhasználók tudják létrehozni az új posztot.
Route::post('/posts', [ApiController::class, 'store'])->middleware('auth:sanctum');
//A Route::patch('/posts/{post}', [ApiController::class, 'update'])->middleware('auth:sanctum'); sor azt jelenti, hogy amikor egy PATCH kérést küldenek a /api/posts/{post} útvonalra (ahol {post} egy változó, amely a poszt azonosítóját jelöli), az ApiController osztály update metódusa fogja kezelni azt. Ez a metódus frissíti a megadott azonosítóval rendelkező posztot az adatbázisban. A middleware('auth:sanctum') rész azt jelenti, hogy csak hitelesített felhasználók tudják frissíteni a posztot.
Route::patch('/posts/{post}', [ApiController::class, 'update'])->middleware('auth:sanctum');
//A Route::get('/posts/{post}/categories', [ApiController::class, 'indexCategories']); sor azt jelenti, hogy amikor egy GET kérést küldenek a /api/posts/{post}/categories útvonalra (ahol {post} egy változó, amely a poszt azonosítóját jelöli), az ApiController osztály indexCategories metódusa fogja kezelni azt. Ez a metódus visszaadja a megadott azonosítóval rendelkező poszthoz tartozó kategóriákat.
Route::get('/posts/{post}/categories', [ApiController::class, 'indexCategories']);
// A Route::patch('/posts/{post}/categories', [ApiController::class, 'updateCategories']); sor azt jelenti, hogy amikor egy PATCH kérést küldenek a /api/posts/{post}/categories útvonalra (ahol {post} egy változó, amely a poszt azonosítóját jelöli), az ApiController osztály updateCategories metódusa fogja kezelni azt. Ez a metódus frissíti a megadott azonosítóval rendelkező poszthoz tartozó kategóriákat.
Route::patch('/posts/{post}/categories', [ApiController::class, 'updateCategories']);
