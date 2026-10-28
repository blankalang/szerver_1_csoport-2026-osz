@extends('bloglayout')

@section('title', $post->title)

@section('content')
    <h2 class="text-xl">{{ $post->title }}</h2>
    <i>{{ $post->author->name }}</i>
    {{--  Ha nem null $post->image mező, azaz van feltöltött kép, akkor lekérjük a Storage disk 'public' mappájában található 'images/' mappából a fájl URL-jét
    és megjelenítjük az <img> tag-ben.
    src:  Storage::disk('public'): használd a public disket. A config/filesystemsphp-ben: 'public' => ['driver' => 'local','root' => storage_path('app/public'),'url' => '/storage', ] url('images/' . $post -> image): URL-t generál egy fájlhoz. Eredmény: /storage/images/kep.jpg --}}
    @if ($post->image !== null)
        <img src="{{ Storage::disk('public')->url('images/' . $post->image) }}" alt="">
    @endif

    <br>
    {{ $post->content }}
    {{-- A @can egy blade direktíva, ami ellenőrzi, hogy a felhasználónak van-e jogosultsága a megadott művelet végrehajtására az adott erőforráson. Itt konkrétan azt ellenőrizzük, hogy a felhasználó jogosult-e a $post szerkesztésére vagy törlésére. --}}
    @can('update', $post)
        <a href="{{ route('posts.edit', ['post' => $post]) }}">Szerkesztés</a>
    @endcan
    @can('delete', $post)
        <form action="{{ route('posts.destroy', ['post' => $post]) }}" method="POST">
            <!-- A @csrf direktíva a Laravelben a Cross-Site Request Forgery (CSRF) támadások elleni védelemhez szükséges. Ez a direktíva automatikusan generál egy rejtett mezőt a formban, amely tartalmazza a CSRF token-t. Amikor a formot elküldik, a szerver ellenőrzi, hogy a token érvényes-e, így biztosítva, hogy a kérés valóban az alkalmazásból származik, és nem egy rosszindulatú forrásból. -->
            @csrf
            <!-- A @method('DELETE') direktíva a HTML formban jelzi, hogy a form által küldött HTTP kérés DELETE típusú legyen, mivel a HTML formok alapértelmezés szerint csak GET és POST kéréseket támogatnak. Laravelben a @method direktíva segítségével lehetőség van más HTTP metódusok (például PUT, PATCH, DELETE) használatára is a formokon keresztül. Ezt hívjuk method spoofingnek -->
            @method('DELETE')
            <a href="#" onclick="this.closest('form').submit()">Törlés</a>
        </form>
    @endcan

@endsection
