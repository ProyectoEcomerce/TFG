<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @vite('resources/scss/app.scss', 'resources/js/app.js', 'resources/js/confirmAction.js')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg sticky-top" style="background-color: #83B271;">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="/img/logo.png" alt="Logo" width="50" height="50">
                    Ecotech</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        @if (Route::has('login'))
                            @auth
                                @if (auth()->user()->hasRole('admin'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.index') }}" class="nav-link">
                                        <i class="fa fa-cogs"></i> {{ __('Administración') }}
                                    </a>
                                </li>
                                
                                
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">
                                        <i class="fas fa-home"></i> {{ __('Inicio') }}
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ url('/home') }}" class="nav-link">
                                        <i class="fas fa-user"></i> {{ __('Cuenta') }}
                                    </a>
                                </li>
                                
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">{{ __('Iniciar sesión') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">{{ __('Registro') }}</a>
                                </li>
                            @endauth
                        @endif
                        <!-- Idiomas -->
                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(App::getLocale() == 'es')
                                        <span class="flag-icon flag-icon-es"></span>
                                    @else
                                        <span class="flag-icon flag-icon-gb"></span>
                                    @endif
                                </button>                                
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <!-- Si el idioma actual es español, muestra la opción para cambiar a inglés y viceversa -->
                                    @if(App::getLocale() == 'es')
                                        <li><a class="dropdown-item" href="{{ route('changeLanguage', ['locale' => 'en']) }}">🇬🇧 English</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('changeLanguage', ['locale' => 'es']) }}">🇪🇸 Español</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>  
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    
</body>
</html>