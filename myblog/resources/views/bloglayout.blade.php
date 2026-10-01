<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- A @vite(['resources/css/app.css', 'resources/js/app.js']) azt jelenti, hogy a Vite eszközt használjuk a CSS és JS fájlok betöltésére. A resources/css/app.css és resources/js/app.js fájlokat fogja betölteni. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="mx-auto container">
        <div class="grid grid-cols-3">
            <div class="col-span-3">
                <h1>myBlog</h1>
            </div>
            <div class="col-span-2">
                <!-- A @yield('content') azt jelenti, hogy a bloglayout.blade.php fájlban definiált content szekció tartalmát fogja megjeleníteni. A @section('content') és @yield('content') együtt használva lehetővé teszi, hogy a child view (pl. posts/index.blade.php) tartalmát a parent view (bloglayout.blade.php) megfelelő helyén jelenítsük meg. -->
                    @yield('content')
                </div>
                <div class="col-span-1">
                    Sidebar
                </div>
            </div>
        </div>
    </body>



    </html>
