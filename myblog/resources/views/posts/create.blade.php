@extends('bloglayout')

@section('title', 'Új bejegyzés létrehozása')

@section('content')

    <h2 class="text-2xl">
        Új bejegyzés létrehozása</h2>
    {{-- Ezzel az űrlappal egy új posztot tudunk létrehozni. Az action attribútum a posts.store route-jához kapcsolódik. A method attribútum a POST kérés típusát adja meg. --}}
    <form action="{{ route('posts.store') }}" method="POST">
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
        Szerző:
        <select name="author_id">
            {{-- Végig iterálunk az összes felhasználón, és létrehozunk egy option elemet minden felhasználóhoz. Az option value attribútuma a felhasználó id-je, a megjelenített szöveg pedig a felhasználó neve. --}}
            @foreach ($users as $user)
                {{-- Az állapottartás miatt az option elemeket is az old() függvénnyel állítjuk be. Ha a felhasználó korábban kiválasztotta a szerzőt, akkor az old('author_id') értéke megegyezik a felhasználó id-jével, és az option be lesz jelölve. Az old('author_id', -1) kifejezés azt jelenti, hogy ha nincs előzőleg bevitt érték, akkor az -1 értéket adjuk vissza, ami biztosan nem egyezik meg egyetlen felhasználó id-jével sem. --}}
                <option value="{{ $user->id }}" {{ old('author_id', -1) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}</option>>
            @endforeach
        </select><br>
        {{-- Az állapottartás miatt a checkbox értékét is az old() függvénnyel állítjuk be. Ha a felhasználó korábban bepipálta a checkboxot, akkor az old('is_public') értéke true lesz, és a checkbox be lesz pipálva. Ha nem pipálta be, akkor az old('is_public') értéke false lesz, és a checkbox üres marad. --}}
        Publikus? <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
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
