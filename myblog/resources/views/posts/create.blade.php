@extends('bloglayout')

@section('title', 'Új bejegyzés létrehozása')

@section('content')

    <h2 class="text-2xl">
        Új bejegyzés létrehozása</h2>
    {{-- Ezzel az űrlappal egy új posztot tudunk létrehozni. Az action attribútum a posts.store route-jához kapcsolódik. A method attribútum a POST kérés típusát adja meg. --}}
    <form action="{{ route('posts.store') }}" method="POST">
        {{-- ide valami lényeges jön --}}
        {{-- A @error('title') direktíva segítségével ellenőrizzük, hogy van-e hiba a title mezőben. Ha van, akkor a {{ $message }} változó tartalmazza a hibaüzenetet, amit megjelenítünk a felhasználónak. A PostController store() metódusában validáltuk a bemenetet, azért lesznek automatikusan generált hibaüzenetek. --}}
        Cím: @error('title')
            {{ $message }}
        @enderror
        <br>
        {{-- Az old() függvény segítségével vissza tudjuk adni a felhasználónak az előzőleg bevitt értéket, ha a validáció sikertelen volt. Ha nincs előzőleg bevitt érték, akkor az üres stringet adjuk vissza. Ezt hívjuk állapottartásnak. --}}
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
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select><br>
        Publikus? <input type="checkbox" name="is_public"><br>

        <h3 class="text-xl">Kategóriák</h3>
        {{-- Végig iterálunk az összes kategórián, és létrehozunk egy checkbox elemet minden kategóriához. --}}
        @foreach ($categories as $category)
            <input type="checkbox" class="mr-2" name="categories[]" value="{{ $category->id }}">
            <span style="color: {{ $category->color }}">{{ $category->name }}</span><br>
        @endforeach

        <button class="mt-2 p-2 bg-sky-500 hover:bg-sky-400 rounded rounded-lg" type="submit">Mentés</button>
    </form>

@endsection
