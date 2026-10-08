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


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
