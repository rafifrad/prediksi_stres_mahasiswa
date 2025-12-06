<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Prediksi Stres Mahasiswa')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ Auth::check() && Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                        Prediksi Stres Mahasiswa
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Admin Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Data Pengguna
                            </a>
                            <a href="{{ route('admin.predictions') }}" class="{{ request()->routeIs('admin.predictions') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Daftar Prediksi
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('questionnaire') }}" class="{{ request()->routeIs('questionnaire') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Kuesioner
                            </a>
                            <a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                                History
                            </a>
                        @endif
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin logout?')">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-8">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

    <div id="logoutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeLogoutModal(event)">
        <div class="relative mx-auto p-6 border w-full max-w-md shadow-2xl rounded-lg bg-white transform transition-all" onclick="event.stopPropagation()">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100">
                <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            
            <!-- Title -->
            <div class="mt-5 text-center">
                <h3 class="text-xl leading-6 font-semibold text-gray-900">
                    Konfirmasi Logout
                </h3>
                <div class="mt-3 px-4 py-2">
                    <p class="text-sm text-gray-600">
                        Apakah Anda yakin ingin keluar dari sistem? Anda perlu login kembali untuk mengakses aplikasi.
                    </p>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 mt-6">
                <button 
                    onclick="closeLogoutModal()" 
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-base font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                    Tidak
                </button>
                <button 
                    onclick="confirmLogout()" 
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white text-base font-medium rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutModal(event) {
            if (!event || event.target === event.currentTarget) {
                document.getElementById('logoutModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLogoutModal();
            }
        });
    </script>
</body>
</html>

