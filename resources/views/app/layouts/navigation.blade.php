@if(Auth::guard('student')->check())
    <nav class="bg-gradient-to-r from-indigo-600 to-blue-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left: Logo and Title -->
                <div class="flex items-center space-x-3">
                    <a href="/student/dashboard" class="flex items-center">                   
                        <span class="ml-2 text-xl font-bold text-white tracking-wide">Student Portal</span>
                    </a>
                </div>
                
                <div class="relative ml-4" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <div class="h-9 w-9 rounded-full bg-white flex items-center justify-center text-indigo-600 font-bold shadow">
                            {{ strtoupper(substr(Auth::guard('student')->user()->name, 0, 1)) }}
                        </div>
                        <span class="ml-2 text-white font-medium">{{ Auth::guard('student')->user()->name }}</span>
                        <svg class="ml-1 h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                        <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@else
<nav x-data="{ open: false, notificationsOpen: false, searchOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" @click="$dispatch('toggle-sidebar')">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Search -->
                <div class="ml-4 flex-1 flex items-center">
                    <div class="max-w-lg w-full">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input id="search" name="search" @focus="searchOpen = true" @blur="searchOpen = false" class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md leading-5 bg-gray-50 dark:bg-gray-900 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 sm:text-sm" placeholder="Search for subjects, students, or activities..." type="search">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <!-- Create New Button -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="bg-indigo-600 dark:bg-indigo-500 p-1 rounded-full text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            <span class="sr-only">Create new</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                        <a href="{{ route('subjects.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="fas fa-book mr-2 text-indigo-500 dark:text-indigo-400"></i> New Subject
                        </a>
                        <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="fas fa-user-graduate mr-2 text-green-500 dark:text-green-400"></i> New Student
                        </a>
                        <a href="{{ route('activities.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="fas fa-tasks mr-2 text-purple-500 dark:text-purple-400"></i> New Activity
                        </a>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="bg-white dark:bg-gray-800 p-1 rounded-full text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-indigo-600 dark:text-indigo-400"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New student enrolled</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">John Doe enrolled in Mathematics</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 hours ago</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center">
                                            <i class="fas fa-tasks text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New submission</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Jane Smith submitted Assignment #3</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Yesterday</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center">
                            <a href="#" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">View all notifications</a>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button type="button" class="ml-3 bg-white dark:bg-gray-800 p-1 rounded-full text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" x-data @click="$dispatch('toggle-theme')">
                    <span class="sr-only">Toggle theme</span>
                    <svg x-show="!document.documentElement.classList.contains('dark')" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="document.documentElement.classList.contains('dark')" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <!-- Profile dropdown -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="bg-white dark:bg-gray-800 rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-300">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="fas fa-user mr-2"></i> Your Profile
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Search Results Dropdown -->
<div x-show="searchOpen" @click.away="searchOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-16 left-0 right-0 mx-auto max-w-lg rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-10" style="display: none;">
    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Search Results</h3>
    </div>
    <div class="max-h-60 overflow-y-auto">
        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Subjects</h4>
        </div>
        <a href="#" class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">Mathematics</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">MATH101</p>
        </a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">Science</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">SCI101</p>
        </a>

        <div class="px-4 py-2 border-b border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Students</h4>
        </div>
        <a href="#" class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">John Doe</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">john.doe@example.com</p>
        </a>

        <div class="px-4 py-2 border-b border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Activities</h4>
        </div>
        <a href="#" class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">Midterm Exam</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Mathematics</p>
        </a>
    </div>
    <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center">
        <a href="#" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">View all results</a>
    </div>
</div>
@endif
