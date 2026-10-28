@extends('bloglayout')

@section('title', 'Új bejegyzés létrehozása')

@section('content')

    <h2 class="text-2xl">
        Új bejegyzés létrehozása</h2>
    {{-- Ezzel az űrlappal egy új posztot tudunk létrehozni. Az action attribútum a posts.store route-jához kapcsolódik. A method attribútum a POST kérés típusát adja meg. --}}
    {{-- Az enctype attribútum a multipart/form-data értéket kapja, ami lehetővé teszi a fájlok feltöltését az űrlapon keresztül. Aa hiányzik, akkornem megy át a fájl, akkor fájlt nem, csak szöveget küld át. A multipart/form-data lehetővé teszi, hogy az űrlap fájlokat (pl. képeket) is elküldjön a szervernek.Az enctype meghatározza, hogyan csomagolja össze a böngésző az űrlap adatait elküldéskor. multipart/form-data: képes bináris adatot küldeni (pl. kép), az adatokat részekre (multipart) bontja
--}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- A @error('title') direktíva segítségével ellenőrizzük, hogy van-e hiba a title mezőben. Ha van, akkor a {{ $message }} változó tartalmazza a hibaüzenetet, amit megjelenítünk a felhasználónak. A PostController store() metódusában validáltuk a bemenetet, azért lesznek automatikusan generált hibaüzenetek. --}}
        Cím: @error('title')
            {{ $message }}
        @enderror
        <br>
        {{-- Az old() függvény segítségével vissza tudjuk adni a felhasználónak az előzőleg bevitt értéket, ha a validáció sikertelen volt. Ha nincs előzőleg bevitt érték, akkor az üres stringet adjuk vissza. Ezt hívjuk állapottartásnak. Ha ráfrissítünk az oldalra, a korábban bevitt értékek már nem lesznek elérhetőek, mert a Laravel flash sessionként kezeli az old() adatait. --}}
        <input type="text" name="title" value="{{ old('title', '') }}" class="w-full"><br>
        Tartalom: @error('content')
            {{ $message }}
        @enderror
        <br>
        <textarea rows="5" name="content" class="w-full">{{ old('content', '') }}</textarea><br>

        {{-- Az állapottartás miatt a checkbox értékét is az old() függvénnyel állítjuk be. Ha a felhasználó korábban bepipálta a checkboxot, akkor az old('is_public') értéke true lesz, és a checkbox be lesz pipálva. Ha nem pipálta be, akkor az old('is_public') értéke false lesz, és a checkbox üres marad. --}}
        Publikus? <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
        Kép: <input type="file" name="image_file"><br>
        <h3 class="text-xl">Kategóriák</h3>
        {{-- Végig iterálunk az összes kategórián, és létrehozunk egy checkbox elemet minden kategóriához. --}}
        @foreach ($categories as $category)
            {{-- Az állapottartás miatt a checkbox értékét is az old() függvénnyel állítjuk be. Ha a felhasználó korábban bepipálta a checkboxot, akkor az old('categories', []) értéke tartalmazza a kategória id-jét, és a checkbox be lesz pipálva. --}}
            <input type="checkbox" class="mr-2" name="categories[]" value="{{ $category->id }}"
                {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
            </option>
            <span style="color: {{ $category->color }}">{{ $category->name }}</span><br>
        @endforeach

        <button class="mt-2 p-2 bg-sky-500 hover:bg-sky-400 rounded rounded-lg" type="submit">Mentés</button>
    </form>

@endsection
