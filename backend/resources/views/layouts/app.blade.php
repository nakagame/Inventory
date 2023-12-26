<?php
    session_start();
?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Original Script -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- css  --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('index') }}">{{ __('Products') }}</a>
                        </li>
                        <li class="nav-item">
                            {{-- <a class="nav-link" href="">{{ __('Wallet') }}</a> --}}
                            <button type="button" class="nav-link btn btn-white" data-bs-toggle="modal" data-bs-target="#wallet">
                                {{ __('Wallet') }}
                            </button>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(Auth::user()->image)
                                        <img src="{{ asset('storage/profiles/'. Auth::user()->image) }}" alt="{{ Auth::user()->name }}" class="rounded circle" style="width: 40px; height: 40px;">                                
                                    @else
                                        <img src="https://thumb.ac-illust.com/73/7387030e5a5600726e5309496353969a_t.jpeg" alt="{{ Auth::user()->name }}" class="rounded circle" style="width: 40px; height: 40px;">                                
                                    @endif 
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->role !== 1)
                                        <a class="dropdown-item" href="{{ route('profile.index', Auth::user()->id) }}">
                                            {{ __('Profile') }}
                                        </a>
                                    @endif
                                    
                                    @if (Auth::user()->role === 3)
                                        {{-- admin  --}}
                                        <a class="dropdown-item" href="{{ route('admin.index') }}">
                                            {{ __('Admin') }}
                                        </a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Wallet -->
    <div class="modal fade" id="wallet" tabindex="-1" aria-labelledby="walletLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title h-3 fw-bold" id="walletLabel">Tou-up</h1>
                </div>
                
                <form action="{{ route('wallet.store') }}" method="post">
                    @csrf

                    <div class="modal-body">
                        @isset(Auth::user()->wallet->amount)
                            <p class="h5">Wallet: $ <span>{{ Auth::user()->wallet->amount }}</span></p>
                        @endisset ()
                        
                        <input type="radio" name="wallet" class="btn-check" id="btn-check" autocomplete="off" value="100">
                        <label class="btn btn-outline-warning" for="btn-check">$100</label>

                        <input type="radio" name="wallet" class="btn-check" id="btn-check-300" autocomplete="off" value="300">
                        <label class="btn btn-outline-warning" for="btn-check-300">$300</label>

                        <input type="radio" name="wallet" class="btn-check" id="btn-check-500" autocomplete="off" value="500">
                        <label class="btn btn-outline-warning" for="btn-check-500">$500</label>

                        <input type="radio" name="wallet" class="btn-check" id="btn-check-1000" autocomplete="off" value="1000">
                        <label class="btn btn-outline-warning" for="btn-check-1000">$1,000</label>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add to Wallet</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
