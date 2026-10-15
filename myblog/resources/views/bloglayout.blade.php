<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>myBlog- @yield('title')</title>
    <!-- A @vite(['resources/css/app.css', 'resources/js/app.js']) azt jelenti, hogy a Vite eszközt használjuk a CSS és JS fájlok betöltésére. A resources/css/app.css és resources/js/app.js fájlokat fogja betölteni. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="mx-auto container px-4">
        <div class="grid grid-cols-3">
            <div class="col-span-3">
                <h1>myBlog</h1>
            </div>
            <div class="col-span-2">
                {{--   A @yield('content') azt jelenti, hogy a bloglayout.blade.php fájlban definiált content szekció tartalmát fogja megjeleníteni. A @section('content') és @yield('content') együtt használva lehetővé teszi, hogy a child view (pl. posts/index.blade.php) tartalmát a parent view (bloglayout.blade.php) megfelelő helyén jelenítsük meg. --}}
                @yield('content')
            </div>
            <div class="col-span-1">
                {{--  A @guest direktíva azt jelenti, hogy ha a felhasználó nincs bejelentkezve, akkor a következő blokkot hajtjuk végre. A @auth direktíva azt jelenti, hogy ha a felhasználó be van jelentkezve, akkor a következő blokkot hajtjuk végre. --}}
                @guest
                    <a href="{{ route('login') }}">Bejelentkezés</a><br>
                    <a href="{{ route('register') }}">Regisztráció</a>
                @endguest
                @auth
                    Szia, {{ Auth::user()->name }}!
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <!-- A kijelentkezés gomb egy link, ami a form elküldését váltja ki. A this.closest('form').submit() JavaScript kód segítségével a legközelebbi form elemet küldi el, ami a kijelentkezéshez szükséges POST kérést indítja el. erre azért van szükség, mert a kijelentkezéshez POST kérés szükséges, nem GET. -->
                        <a href="#" onclick="this.closest('form').submit()">Kijelentkezés</a>
                    </form>
                @endauth

            </div>
        </div>
    </div>
</body>



</html>
