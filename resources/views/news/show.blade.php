@extends('layouts.app')

{{--@section('title', 'Содержание статьи "'.$news->title.'"')--}}

@section('header')
    <h1 class = "ml-4 mt-3">{{ $news->title }}</h1>
    
@endsection 


@section('content')

<div class = "ml-4 ">
    {!! $news->content !!}
</div>

<div class = "ml-4 mt-3">
    <a href="/news"><< К списку статей</a>
</div>
@endsection