<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @vite('resources/css/app.css', 'resources/js/app.js', 'resources/js/confirmAction.js')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!--Font Awesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!--Jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg sticky-top header" >
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="/images/logo.png" alt="Logo" width="100" height="50">
                    TenTwoTurno</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        @if (Route::has('login'))
                            @auth
                               
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">
                                        <i class="fas fa-home"></i> {{ __('Inicio') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('availability') }}" class="nav-link">
                                        <i class="fas fa-calendar-alt"></i> {{ __('Mis disponibilidades') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('tourn') }}" class="nav-link">
                                        <i class="fas fa-calendar-alt"></i> {{ __('Mis turnos') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('home') }}" class="nav-link">
                                        <i class="fas fa-user"></i> {{ __('Cuenta') }}
                                    </a>
                                </li>

                                @if (auth()->user()->hasRole('admin'))
                                <li class="nav-item">
                                    <a href="{{ route('viewAdmin') }}" class="nav-link">
                                        <i class="fa fa-cogs"></i> {{ __('Administración') }}
                                    </a>
                                </li>
                                @endif
                                
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">{{ __('Iniciar sesión') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">{{ __('Registro') }}</a>
                                </li>
                            @endauth
                        @endif 
                    </ul>
                </div>
            </div>
        </nav>
    </header>
     <div class="container">
    <main>
       
            @yield('content')
      
    </main>
  </div>
    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 d-flex align-items-center">
                    <span class="text-muted">© 2024 102turno</span>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    @yield('scripts')
</body>
</html>