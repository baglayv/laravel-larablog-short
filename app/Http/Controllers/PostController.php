<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // не аутентифицированные пользователи могут только просматривать
        $this->middleware('auth')->except('index', 'show', 'search');
    }
    
    public function index()
    {
        //
        $posts = Post::select('posts.*', 'users.name as author')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(6)
          ;

        //$posts = Post::paginate(4);

        return view('posts.index', compact('posts'));
    }


    public function search(Request $request)
    {
        $search = $request->input('search', '');
        // обрезаем слишком длинный запрос
        $search = iconv_substr($search, 0, 64);
        // удаляем все кроме букв и цифр
        $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
        // сжимаем двойные пробелы
        $search = preg_replace('#\s+#u', ' ', $search);

        if(empty($search)) {
            return view('posts.search');
        }

        $posts = Post::select('posts.*', 'users.name as author')
                ->join('users', 'posts.author_id', '=', 'users.id')
                ->where('posts.title', 'like', '%'.$search.'%') // поиск по заголовку поста
                ->orWhere('posts.body', 'like', '%'.$search.'%') //поиск по тексту поста
                ->orWhere('users.name', 'like', '%'.$search.'%') //поиск по автору поста
                ->orderBy('posts.created_at', 'desc')
                ->paginate(4)
                ->appends(['search' => $request->input('search')]);;
        return view('posts.search', compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //
        $post = new Post();
        // Автор поста - текущий пользователь
        $post->author_id = Auth::id();
        $post->title = $request->input('title');
        $post->excerpt = $request->input('excerpt');
        $post->body = $request->input('body');
        $this->uploadImage($request, $post);
        
        $post->save();
        return redirect()->route('post.index')->with('success', 'Новый пост успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::select('posts.*', 'users.name as author')
                ->join('users', 'posts.author_id', '=', 'users.id')
                ->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::findOrFail($id);
        // Пользователь может редактировать только свои посты
        if (!$this->checkRights($post)) {
            return redirect()
                ->route('post.index')
                ->withErrors('Вы можете редактировать только свои посты');
        }
        return view('posts.edit', compact('post'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        //
        $post = Post::findOrFail($id);
        // Пользователь может редактировать только свои посты
        if (!$this->checkRights($post)) {
            return redirect()
                ->route('post.index')
                ->withErrors('Вы можете редактировать только свои поcты');
        }
        $post->title = $request->input('title');
        $post->excerpt = $request->input('excerpt');
        $post->body = $request->input('body');
        // если надо удалить старое изображение
        if ($request->input('remove')) {
            $this->removeImage($post);
        }
        // если было загружено новое изображение
        $this->uploadImage($request, $post);
        // все готово, можно сохранять пост в БД
        $post->update();
        return redirect()
                ->route('post.show', compact('post'))
                ->with('success', 'Пост успешно отредактирован');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::findOrFail($id);
        // Пользователь может удалять только свои посты
        if (!$this->checkRights($post)) {
            return redirect()
                ->route('post.index')
                ->withErrors('Вы можете удалять только свои посты');
        }
        $this->removeImage($post);
        $post->delete();
        return redirect()
            ->route('post.index')
            ->with('success', 'Пост был успешно удален');
    }

    private function uploadImage(Request $request, Post $post)
    {
        $source = $request->file('image');
        if ($source) {
            // перед тем, как загружать новое изображение, удаляем загруженное ранее
            $this->removeImage($post);

            // сохраняем исходное изображение и создаем 2 копии 1200х400 и 600х200
            $ext = str_replace('jpeg', 'jpg', $source->extension());
            // уникальное имя файла, под которым сохраним его в storage/image/source
            $name = md5(uniqid());
            Storage::putFileAs('public/image/source', $source, $name. '.' .$ext);
            // создаем jpg изображения для страницы с постами размером 1200х400, качество 100%
            $image = Image::make($source)
                ->resizeCanvas(1200, 400, 'center', false, 'dddddd')
                ->encode('jpg', 100);
            // сохраняем это изображение под именем $name.jpg в директории public/image/image
            Storage::put('public/image/image/' . $name . '.jpg', $image);
            $image->destroy();
            $post->image = Storage::url('public/image/image/' . $name . '.jpg');
            // сохраняем jpg изображение для списка постов блога размером 600х200, качество 100%
            $thumb = Image::make($source)
                ->resizeCanvas(600, 200, 'center', false, 'dddddd')
                ->encode('jpg', 100);
            // сохраняем это изображение под именем $name.jpg в директории public/image/thumb
            Storage::put('public/image/thumb/' . $name . '.jpg', $thumb);
            $thumb->destroy();
            $post->thumb = Storage::url('public/image/thumb/' . $name . '.jpg');       
        }
    }

    private function removeImage(Post $post)
    {
        if (!empty($post->image)) {
            $name = basename($post->image);
            if (Storage::exists('public/image/image/' . $name)) {
                Storage::delete('public/image/image/' . $name);
            }
            $post->image = null;
        }
        
        if (!empty($post->thumb)) {
            $name = basename($post->thumb);
            if (Storage::exists('public/image/thumb/' . $name)) {
                Storage::delete('public/image/thumb/' . $name);
            }
            $post->thumb = null;
        }

        // Здесь сложнее, мы не знаем, какое у файла разрешение
        if (!empty($name)) {
            $images = Storage::files('public/image/source');
            $base = pathinfo($name, PATHINFO_FILENAME);
            foreach ($images as $img) {
                $temp = pathinfo($img, PATHINFO_FILENAME);
                if ($temp == $base) {
                    Storage::delete($img);
                    break;
                }
            }
        }
    }
    
    // Проверяет права пользователя на редактирование и удаление поста 
    // (автор пста или пользователь с id = 5 )
    private function checkRights(Post $post)
    {
        return Auth::id() == $post->author_id || Auth::id() == 5;
    }
}
