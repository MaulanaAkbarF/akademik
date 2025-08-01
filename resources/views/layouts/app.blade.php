<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> Register User
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.sks.index') }}">
                                        <i class="fas fa-money-bill-wave"></i> Kelola SKS
                                    </a>
                                </li>
                            @elseif(Auth::user()->isDosen())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dosen.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dosen.jadwal') }}">
                                        <i class="fas fa-calendar"></i> Jadwal
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dosen.presensi.index') }}">
                                        <i class="fas fa-clipboard-list"></i> Presensi
                                    </a>
                                </li>
                            @elseif(Auth::user()->isMahasiswa())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('mahasiswa.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('mahasiswa.jadwal') }}">
                                        <i class="fas fa-calendar"></i> Jadwal
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('mahasiswa.presensi.index') }}">
                                        <i class="fas fa-clipboard-check"></i> Presensi
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="sksDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-graduation-cap"></i> Akademik
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('mahasiswa.sks.index') }}">
                                            <i class="fas fa-wallet"></i> Saldo SKS
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('mahasiswa.krs.index') }}">
                                            <i class="fas fa-book"></i> Ambil Mata Kuliah
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('mahasiswa.sks.create') }}">
                                            <i class="fas fa-shopping-cart"></i> Beli SKS
                                        </a></li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
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
                        @else
                            <!-- Current Date and Time -->
                            <li class="nav-item d-flex align-items-center me-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-day"></i> {{ now()->format('d M Y') }} |
                                    <i class="fas fa-clock"></i> <span id="current-time">{{ now()->format('H:i:s') }}</span>
                                </small>
                            </li>

                            <!-- SKS Balance for Mahasiswa -->
                            @if(Auth::user()->isMahasiswa() && Auth::user()->mahasiswa)
                                @php
                                    $sksSaldo = Auth::user()->mahasiswa->sksSaldo;
                                @endphp
                                @if($sksSaldo)
                                    <li class="nav-item d-flex align-items-center me-3">
                                        <div class="text-center">
                                            <small class="text-primary">
                                                <i class="fas fa-graduation-cap"></i> 
                                                SKS: {{ $sksSaldo->getSksRemainingAttribute() }}/{{ $sksSaldo->sks_tersedia }}
                                            </small>
                                            @if($sksSaldo->getSksRemainingAttribute() <= 3)
                                                <br><small class="text-danger">SKS Hampir Habis!</small>
                                            @endif
                                        </div>
                                    </li>
                                @endif
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }} 
                                    <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'dosen' ? 'warning' : 'info') }}">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->isMahasiswa())
                                        <a class="dropdown-item" href="{{ route('mahasiswa.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                        <a class="dropdown-item" href="{{ route('mahasiswa.jadwal') }}">
                                            <i class="fas fa-calendar"></i> Jadwal Kuliah
                                        </a>
                                        <a class="dropdown-item" href="{{ route('mahasiswa.presensi.index') }}">
                                            <i class="fas fa-clipboard-check"></i> Presensi
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('mahasiswa.sks.index') }}">
                                            <i class="fas fa-wallet"></i> Saldo SKS
                                        </a>
                                        <a class="dropdown-item" href="{{ route('mahasiswa.krs.index') }}">
                                            <i class="fas fa-book"></i> KRS
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @elseif(Auth::user()->isDosen())
                                        <a class="dropdown-item" href="{{ route('dosen.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                        <a class="dropdown-item" href="{{ route('dosen.jadwal') }}">
                                            <i class="fas fa-calendar"></i> Jadwal Mengajar
                                        </a>
                                        <a class="dropdown-item" href="{{ route('dosen.presensi.index') }}">
                                            <i class="fas fa-clipboard-list"></i> Kelola Presensi
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @elseif(Auth::user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.sks.index') }}">
                                            <i class="fas fa-money-bill-wave"></i> Kelola SKS
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
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
            @yield('content')
        </main>
    </div>

    <!-- Real-time clock update -->
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toTimeString().split(' ')[0];
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        // Update time every second
        setInterval(updateTime, 1000);
        
        // Initial update
        updateTime();
    </script>
</body>
</html>