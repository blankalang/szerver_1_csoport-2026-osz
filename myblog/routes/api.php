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
Route::get('/posts/{post}', [ApiController::class, 'show']);
