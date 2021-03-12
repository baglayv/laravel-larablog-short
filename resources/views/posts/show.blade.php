@extends('layouts.site', ['title' => $post->title])

@section('content')
    <div class = "row">
        <div class = "col-12">
            <div class = "card mt-4 mb-4">
                <div class = "card-header">
                    <h1>{{ $post->title}}</h1>
                </div>
                <div class = "card-body">
                    <img src = "{{ $post->image ?? asset('storage/rock_sea_image.jpg') }}" alt = "" class = "img-fluid">
                    <p class = "mt-3 mb-0">{{ $post->body }}</p>
                </div>
                <div class = "card-footer">
                    <div class = "card-footer">
                        <div class = "clearflex">
                            <span class = "float-left">
                                Автор: {{ $post->author }}
                                <br>
                                Дата: {{ date_format($post->created_at, 'd.m.Y H:i')}}
                            </span>
                            <span class = "float-right">
                            @auth <!-- Только аутентифицированные пользователи могут редактировать и удалять -->
                                {{--@if (auth()->id() == $post->author_id) --}} <!-- ..причем, только свои посты блога -->
                                    <a href = "{{ route('post.edit', ['post' => $post->post_id]) }}" 
                                    class = "btn btn-dark mr-2">Редактировать</a>
                                    <!-- Форма для удаления поста -->
                                    <form action = "{{ route('post.destroy', ['post' => $post->post_id]) }}"
                                        method = "post" onsubmit = "return confirm('Удалить этот пост?')" 
                                        class = "d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type = "submit" class = "btn btn-danger" value = "Удалить">
                                    </form>
                               {{-- @endif --}}
                            @endauth
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection