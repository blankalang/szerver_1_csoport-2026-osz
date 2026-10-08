{{-- a @extends('bloglayout') azt jelenti, hogy a bloglayout.blade.php fájlban lévő elrendezést használjuk --}}
@extends('bloglayout')
{{-- a @section('title') azt jelenti, hogy a bloglayout.blade.php fájlban definiált title szekció tartalmát szerkesztjük --}}
@section('title', 'Kezdőlap')
{{-- Azértnemkellzáróbladetag,merta@section('title')egyrövidítettszintaxis,amiautomatikusanlezárjaaszekciót. --}}
{{-- a @section('content') azt jelenti, hogy a bloglayout.blade.php fájlban definiált content szekció tartalmát szerkesztjük --}}
@section('content')
    <ul>
        {{-- Egy lista az összes poszt címéről --}}
        @foreach ($posts as $post)
            {{-- A href attribútum a posts.show route-jához kapcsolódik. A route paramétereként a $post objektumot adjuk át, így a route generálja a megfelelő URL-t a poszt megtekintéséhez. --}}
            <li><a href="{{ route('posts.show', ['post' => $post]) }}">{{ $post->title }}</a>
                <i> {{ $post->author->name }} </i>
            </li>
        @endforeach
    </ul>
@endsection
