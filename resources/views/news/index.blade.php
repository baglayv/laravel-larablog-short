@extends('layouts.app')

@section('title', 'Список статей')

@section('header')
    <h1 class = " mt-3">Список статей</h1>
@endsection

@section('content')

<div >
    
    @foreach ($news as $new)
        <div><a href = "/news/{{ $new->id}}">{{ $new->title}}</a></div>
    @endforeach
    
</div>

<div class = "mt-3">
{{ $news->links() }}
</div>
@endsection