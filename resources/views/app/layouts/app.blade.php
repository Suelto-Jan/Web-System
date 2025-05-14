<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ tenant('name') ?? config('app.name', 'Laravel') }} - Classroom</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styles -->
        <style>
            :root {
                --primary-color: #4f46e5;
                --primary-hover: #4338ca;
                --secondary-color: #0ea5e9;
                --accent-color: #8b5cf6;
            }

            body {
                font-family: 'Inter', sans-serif;
            }

            .bg-primary {
                background-color: var(--primary-color);
            }

            .text-primary {
                color: var(--primary-color);
            }

            .border-primary {
                border-color: var(--primary-color);
            }

            .hover\:bg-primary-hover:hover {
                background-color: var(--primary-hover);
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #c5c5c5;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            .dark ::-webkit-scrollbar-track {
                background: #1f2937;
            }

            .dark ::-webkit-scrollbar-thumb {
                background: #4b5563;
            }

            .dark ::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }

            /* Card hover effects */
            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            /* Animated gradient background */
            .animated-gradient {
                background: linear-gradient(-45deg, #4f46e5, #0ea5e9, #8b5cf6, #ec4899);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }

            @keyframes gradient {
                0% {
                    background-position: 0% 50%;
                }
                50% {
                    background-position: 100% 50%;
                }
                100% {
                    background-position: 0% 50%;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <div class="flex h-screen overflow-hidden">
                <!-- Sidebar -->
                @if(Auth::guard('student')->check())
                    @include('app.layouts.student-sidebar')
                @else
                @include('app.layouts.sidebar')
                @endif

                <!-- Main Content -->
                <div class="flex-1 overflow-auto">
                    <!-- Top Navigation -->
                    @include('app.layouts.navigation')

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow-sm">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="pb-8">
                        {{ $slot }}
                    </main>

                    <!-- Footer -->
                    <footer class="bg-white dark:bg-gray-800 shadow-inner py-4 px-4 sm:px-6 lg:px-8">
                        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2 md:mb-0">
                                &copy; {{ date('Y') }} {{ tenant('name') ?? config('app.name') }}. All rights reserved.
                            </div>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
