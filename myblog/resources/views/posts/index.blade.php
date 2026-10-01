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
            <li><a href="">{{ $post->title }}</a> </li>
        @endforeach
    </ul>
@endsection
