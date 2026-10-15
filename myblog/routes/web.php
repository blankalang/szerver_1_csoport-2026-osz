<?php

use App\Http\Controllers\ProfileController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


// Kiszerveztük a Postcontrollerbe a logikát, hogy a web.php fájlban ne legyen túl sok kód. A gyökérútvonalra érkező GET kérést a PostController index() metódusa kezeli.  A ->name('posts.index') a route nevét adja meg, nem a Blade-nézetét! A kettőnek nem kötelező megegyeznie.
Route::get('/', [PostController::class, 'index'])->name('posts.index');
// Ennek a két sornak a sorrendje fontos, mert a Laravel a route-okat felülről lefelé értékeli. Ha a /posts/create route-ot a /posts/{post} route elé helyeznénk, akkor a Laravel a /posts/create útvonalat a /posts/{post} route-nak tekintené, és a create() metódus helyett a show() metódust hívná meg. Ezért mindig a konkrétabb route-okat kell előre helyezni az általánosabbakhoz képest. Ezt úgy hívjuk, hogy mintaillesztés (pattern matching).
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
// A /posts/{post}/edit route a PostController edit() metódusát hívja meg, ami visszaadja az edit.blade.php nézetet, ahol a felhasználó módosíthatja a poszt adatait.
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
//A /posts/{post} route a PostController update() metódusát hívja meg, ami visszaadja a update.blade.php nézetet, ahol a felhasználó módosíthatja a poszt adatait.
Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');


Route::get('/dashboard', function () {
    // A redirect() metódus segítségével átirányítjuk a felhasználót a posts.index route-ra, ami a bejegyzések listáját jeleníti meg.
    return  redirect() -> route('posts.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
