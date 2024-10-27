<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Inventaris Sekolah</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <div class="mx-auto max-w-7xl">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:w-full lg:max-w-2xl lg:pb-28 xl:pb-32">
                <nav class="relative flex items-center justify-between px-4 pt-6 sm:px-6 lg:px-8">
                    <!-- Logo -->
                    <div class="flex flex-shrink-0 items-center">
                        <h1 class="text-3xl font-bold text-blue-600">SIMINV</h1>
                    </div>
                    <!-- Navigation -->
                    <div class="flex space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </nav>

                <!-- Hero Content -->
                <main class="mx-auto mt-10 max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h2 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Sistem Manajemen</span>
                            <span class="block text-blue-600 xl:inline">Inventaris Sekolah</span>
                        </h2>
                        <p class="mt-3 text-base text-gray-500 sm:mx-auto sm:mt-5 sm:max-w-xl sm:text-lg md:mt-5 md:text-xl lg:mx-0">
                            Kelola inventaris sekolah dengan lebih efisien. Sistem manajemen yang memudahkan pencatatan, pelacakan, dan pelaporan aset sekolah.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}"
                                    class="flex w-full items-center justify-center rounded-md bg-blue-600 px-8 py-3 text-base font-medium text-white hover:bg-blue-700 md:px-10 md:py-4 md:text-lg">
                                    Mulai Sekarang
                                </a>
                            </div>
                            <div class="mt-3 sm:ml-3 sm:mt-0">
                                <a href="#features"
                                    class="flex w-full items-center justify-center rounded-md bg-blue-100 px-8 py-3 text-base font-medium text-blue-700 hover:bg-blue-200 md:px-10 md:py-4 md:text-lg">
                                    Pelajari Lebih Lanjut
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:h-full lg:w-full"
                src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80"
                alt="Inventory Management">
        </div>
    </div>

    <!-- Feature Section -->
    <div id="features" class="bg-gray-50 py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Fitur Unggulan</h2>
                <p class="mx-auto mt-3 max-w-2xl text-xl text-gray-500 sm:mt-4">
                    Berbagai fitur yang memudahkan pengelolaan inventaris sekolah
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="pt-6">
                    <div class="flow-root rounded-lg bg-white px-6 pb-8">
                        <div class="-mt-6">
                            <div class="inline-flex items-center justify-center rounded-md bg-blue-600 p-3 shadow-lg">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="mt-8 text-lg font-medium tracking-tight text-gray-900">Pencatatan Mudah</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Catat dan kelola inventaris dengan mudah menggunakan interface yang user-friendly
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="pt-6">
                    <div class="flow-root rounded-lg bg-white px-6 pb-8">
                        <div class="-mt-6">
                            <div class="inline-flex items-center justify-center rounded-md bg-blue-600 p-3 shadow-lg">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="mt-8 text-lg font-medium tracking-tight text-gray-900">Laporan Real-time</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Pantau status inventaris secara real-time dengan laporan yang informatif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="pt-6">
                    <div class="flow-root rounded-lg bg-white px-6 pb-8">
                        <div class="-mt-6">
                            <div class="inline-flex items-center justify-center rounded-md bg-blue-600 p-3 shadow-lg">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="mt-8 text-lg font-medium tracking-tight text-gray-900">Keamanan Terjamin</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Sistem keamanan berlapis untuk melindungi data inventaris sekolah
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white">
        <div class="mx-auto max-w-7xl py-12 px-4 sm:px-6 md:flex md:items-center md:justify-center lg:px-8">
            <div class="mt-8 md:order-1 md:mt-0">
                <p class="text-center text-base text-gray-400">&copy; {{ date('Y') }} Sistem Inventaris Sekolah. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>