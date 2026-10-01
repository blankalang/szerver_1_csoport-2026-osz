<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::get('/', function () {
    return view('welcome');
});

// //1
// Route::get('/contact', function () {
//     return view('contact');
// });

// //2
// Route::get('/contact/{id}', function ($id) {
// return $id;
// });

// //3
// Route::get('/create-post', function () {
//     return 'létrehoztál egy postot';
// });



//A Route::get('/', function () { azt jelenti, hogy amikor a felhasználó a gyökér URL-re (/) navigál, akkor a megadott függvény fut le, ami lekéri az összes posztot az adatbázisból a Post modell segítségével, majd visszaadja a 'posts.index' nézetet, és átadja neki a lekért posztokat egy 'posts' nevű változóban. Nemsokára ugyanezt a logikát áthelyezzük egy PostController osztályba, hogy a kódunk rendezettebb és karbantarthatóbb legyen.
 Route::get('/', function () {
   $posts = Post::all();
    return view('posts.index', ['posts' => $posts]);
 });



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
