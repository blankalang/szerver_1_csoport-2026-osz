@extends('bloglayout')
{{--  másoltuk a create fájlt, és módosítottuk --}}
@section('title', $post->title . ' szerkesztése')

@section('content')

    <h2 class="text-2xl">
        {{ $post->title . ' szerkesztése' }}</h2>
    {{-- itt módosítunk --}}
    {{-- Ezzel az űrlappal egy meglévő posztot tudunk módosítani. Az action attribútum a posts.update route-jához kapcsolódik. A method attribútum a PATCH kérés típusát adja meg, de method spoofinggal kell megoldani. A ['post' => $post] azért kell, mert a route paraméterként várja a poszt objektumot. --}}
    {{-- Az enctype attribútum a multipart/form-data értéket kapja, ami  lehetővé teszi, hogy az űrlap fájlokat (pl. képeket) is elküldjön a szervernek. --}}
    <form action="{{ route('posts.update', ['post' => $post]) }}" method="POST" enctype="multipart/form-data">
        {{-- A legtöbb keretrendszer a post kérést és minden állapottmódosító kérést:POST/PUT/PATCH/DELETE csak úgy teljesíti, ha benne van a csrf token, 419 hibát ad. Volt egy változás a Laravel 13-ban a vcsrf-el kapcsolatban: a Laravel 13 a CSRF-védelem részeként a kérés eredetét is ellenőrzi. Emiatt azonos eredetű böngészőkéréseknél a @csrf hiánya nem feltétlenül okoz 419-es hibát, de az űrlapokban továbbra is használjuk a @csrf direktívát. --}}
        @csrf
        @method('PATCH')
        {{-- A @error('title') direktíva segítségével ellenőrizzük, hogy van-e hiba a title mezőben. Ha van, akkor a {{ $message }} változó tartalmazza a hibaüzenetet, amit megjelenítünk a felhasználónak. A PostController store() metódusában validáltuk a bemenetet, azért lesznek automatikusan generált hibaüzenetek. --}}
        Cím: @error('title')
            {{ $message }}
        @enderror
        <br>
        {{-- Az old() függvény segítségével vissza tudjuk adni a felhasználónak az előzőleg bevitt értéket, ha a validáció sikertelen volt. A  $post->title azért kell üres sztring helyett, mert a poszt objektum címe már be van állítva. --}}
        <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full"><br>
        Tartalom: @error('content')
            {{ $message }}
        @enderror
        <br>
        {{-- A $post->content azért kell, mert a poszt objektum tartalma már létezik. --}}
        <textarea rows="5" name="content" class="w-full">{{ old('content', $post->content) }}</textarea><br>
        {{-- Az old() függvény segítségével vissza tudjuk adni a felhasználónak az előzőleg bevitt értéket, ha a validáció sikertelen volt. A  $post->is_public azért kell üres sztring helyett, mert a poszt objektum publikus állapota már be van állítva.  --}}
        Publikus? <input type="checkbox" name="is_public" {{ old('is_public', $post->is_public) ? 'checked' : '' }}><br>
        <br>
        {{-- Ha a poszthoz van feltöltött kép, akkor megjelenítjük azt. A Storage::disk('public')->url('images/' . $post->image) segítségével lekérjük a kép URL-jét a public/images mappából. Alatta megjelenik az új kép kiválasztása mező. --}}
        @if ($post->image)
            <p>Jelenlegi kép:</p>
            <img src="{{ Storage::disk('public')->url('images/' . $post->image) }}" alt="" width="200">
        @endif
        <p>Új kép kiválasztása:</p>
        <input type="file" name="image_file" accept="image/jpeg,image/png,image/gif">
        @error('image_file')
            <p class="text-red-500">{{ $message }}</p>
        @enderror


        <h3 class="text-xl">Kategóriák</h3>
        {{-- Végig iterálunk az összes kategórián, és létrehozunk egy checkbox elemet minden kategóriához. --}}
        @foreach ($categories as $category)
            <input type="checkbox" class="mr-2" name="categories[]" value="{{ $category->id }}"
                {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }}>

            <span style="color: {{ $category->color }}">{{ $category->name }}</span><br>
        @endforeach

        <button class="mt-2 p-2 bg-sky-500 hover:bg-sky-400 rounded rounded-lg" type="submit">Mentés</button>
    </form>

@endsection
