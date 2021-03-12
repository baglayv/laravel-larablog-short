<!doctype html>
<html lang = "ru">
    <head>
        <meta charset = "utf-8">
        <meta name = "viewport" 
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale-1.0, minimum-scale=1.0">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title>{{ $title ?? 'Веб-разработка'}}</title>
        <!--link rel = "icon" type = "image/png" href = "{{ asset('pet-commands-summon.png')}}" /-->
        <link rel = "icon" type = "image/png" href = "{{ asset('owl.png')}}" />
        <!--link rel = "stylesheet" href = "{{ asset('css/app.css') }}"-->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src = "{{ asset('js/app.js') }}"></script>

    </head>
    <body>
    
        <div class = "container">
            <nav class = "navbar navbar-expand-lg navbar-dark bg-dark">
                <a class = "navbar-brand" href = "/">Мой блог</a>
                <button class = "navbar-toggler" type = "button" data-toggle = "collapse" 
                        data-target = "#navbarSupportedContent" aria-controls = "navbarSupportedContent" 
                        arial-expanded = "false" arial-label = "Toggle navigation">
                    <span class = "navbar-toggle-icon"></span>    
                </button>

                <div class ="collapse navbar-collapse" id = "navbarSupportedContent">
                    <ul class = "navbar-nav mr-auto">
                        <li class = "nav-item">
                            <a class = "nav-link" href = "#">Автор</a>
                        </li>
                        <li class = "nav-item"> 
                            <a class = "nav-link" href = "{{ route('post.create') }}">Создать</a>
                        </li>
                        <li class = "nav-item">
                            <a class = "nav-link" href = "#">Контакты</a>
                        </li>
                    </ul>
                    
                    <form class = "form-inline my-2 my-lg-0 ml-auto" action = "{{ route('post.search')}}">
                        <input class = "form-control mr-sm-2" name = "search" placeholder = "Поиск" arial-label = "Поиск">
                        <button class = "btn btn-outline-success my-2 my-sm-0" type = "submit">Поиск</button>
                    </form>
                    <!-- Ссылки справа -->
                    
                    <ul class="navbar-nav ">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"> <!-- Ссылка для входа-->
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item"> <!-- Ссылка для регистрации -->
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }} <!-- Ссылка выхода-->
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                    <!--  -->
                </div>
            </nav>

            @if ($message = Session::get('success'))
                <div class = "alert alert-success alert-dismissible mt-4" role = "alert">
                    <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Закрыть">
                        <span aria-hudden = "true">&times;</span>
                    </button>
                    {{ $message }}
                </div>
            @endif

            @if ($errors->any())
                <div class = "alert alert-danger alert-dismissible mt-4" role = "alert">
                    <button type = "button" class = "close" data-dismiss = "alert" arial-label = "Закрыть">
                        <span arial-hidden = "true">&times;</span>
                    </button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            

            @yield('content')
            
           
        <div>
    </body>
</html>