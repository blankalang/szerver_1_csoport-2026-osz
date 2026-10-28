{{-- a @extends('bloglayout') azt jelenti, hogy a bloglayout.blade.php fájlban lévő elrendezést használjuk --}}
@extends('bloglayout')
{{-- a @section('title') azt jelenti, hogy a bloglayout.blade.php fájlban definiált title szekció tartalmát szerkesztjük --}}
@section('title', 'Kezdőlap')
{{-- Azértnemkellzáróbladetag,merta@section('title')egyrövidítettszintaxis,amiautomatikusanlezárjaaszekciót. --}}
{{-- a @section('content') azt jelenti, hogy a bloglayout.blade.php fájlban definiált content szekció tartalmát szerkesztjük --}}
@section('content')
    {{--  A @if (Session::has('post-created')) azt jelenti, hogy ha a session-ben van egy 'post-created' kulcs, akkor a következő blokkot hajtjuk végre. A Session::has() metódus ellenőrzi, hogy létezik-e a megadott kulcs a session-ben. --}}
    @if (Session::has('post-created'))
        <div class="bg-green-300 rounded mb-2 py-2 text-center">
            A(z) {{ Session::get('post-created') }} című bejegyzés létrehozva!
        </div>
        {{--  Az elseif ág azt jelenti, hogy ha a fenti feltétel nem teljesül, de a session-ben van egy 'post-updated' kulcs, akkor a következő blokkot hajtjuk végre. A Session::get() metódus lekéri a megadott kulcs értékét a session-ből. --}}
    @elseif (Session::has('post-updated'))
        <div class="bg-green-300 rounded mb-2 py-2 text-center">
            A(z) {{ Session::get('post-updated') }} című bejegyzés módosítva!
        </div>
        {{-- Az elseif ág azt jelenti, hogy ha a fenti feltételek nem teljesülnek, de a session-ben van egy 'post-deleted' kulcs, akkor a következő blokkot hajtjuk végre. --}}
    @elseif (Session::has('post-deleted'))
        <div class="bg-green-300 rounded mb-2 py-2 text-center">
            A(z) {{ Session::get('post-deleted') }} című bejegyzés törölve!
        </div>
    @endif

    <ul>
        {{-- Egy lista az összes poszt címéről --}}
        @foreach ($posts as $post)
            {{-- A href attribútum a posts.show route-jához kapcsolódik. A route paramétereként a $post objektumot adjuk át, így a route generálja a megfelelő URL-t a poszt megtekintéséhez. --}}
            <li><a href="{{ route('posts.show', ['post' => $post]) }}">{{ $post->title }}</a>
                <i> {{ $post->author->name }} </i>
            </li>
        @endforeach
    </ul>
    {{-- A $posts->links() metódus segítségével megjelenítjük a lapozó gombokat. A paginate() metódus automatikusan kezeli az aktuális oldalt a query string alapján, így a felhasználó könnyen navigálhat az oldalak között. --}}
    {{ $posts->links() }}
@endsection
