<div x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || window.innerWidth >= 1024,
    darkMode: localStorage.getItem('darkMode') === 'true' || window.matchMedia('(prefers-color-scheme: dark)').matches,
    notificationsOpen: false,
    notifications: [
        { id: 1, title: 'New application received', time: '5 minutes ago', read: false },
        { id: 2, title: 'Tenant domain activated', time: '1 hour ago', read: false },
        { id: 3, title: 'System update completed', time: '2 days ago', read: true }
    ],
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
    },
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    initDarkMode() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" x-init="initDarkMode()" class="min-h-screen flex flex-col">
    <!-- Sidebar -->
    <div class="flex flex-1 overflow-hidden">
        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:w-64 flex flex-col">
            <!-- Logo and Toggle -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-800">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                        C
                    </div>
                    <span class="ml-3 text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Central</span>
                </a>
                <button @click="toggleSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-purple-50 dark:bg-gray-800 text-purple-600 dark:text-purple-400 border-l-4 border-purple-600 dark:border-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400' }} rounded-md transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Tenant Management Link -->
                @if(auth()->check() && !tenant())
                <a href="{{ route('tenants.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('tenants.*') ? 'bg-purple-50 dark:bg-gray-800 text-purple-600 dark:text-purple-400 border-l-4 border-purple-600 dark:border-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400' }} rounded-md transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Tenant Management</span>
                </a>
                @endif

                @if(auth()->check() && !tenant())
                <a href="{{ route('applications.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('applications.*') ? 'bg-purple-50 dark:bg-gray-800 text-purple-600 dark:text-purple-400 border-l-4 border-purple-600 dark:border-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400' }} rounded-md transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Applications</span>
                    @php
                        $pendingCount = \App\Models\TenantApplication::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                @endif

                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Settings
                    </h3>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('profile.*') ? 'bg-purple-50 dark:bg-gray-800 text-purple-600 dark:text-purple-400 border-l-4 border-purple-600 dark:border-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400' }} rounded-md transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-md transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-gray-800 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold text-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-800">
            <!-- Top Navigation -->
            <div class="bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left: Hamburger -->
                    <div class="flex items-center">
                        <button @click="toggleSidebar" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="ml-4 lg:hidden">
                            <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ request()->routeIs('dashboard') ? 'Dashboard' : (request()->routeIs('tenants.*') ? 'Tenant Management' : (request()->routeIs('applications.*') ? 'Applications' : 'Central')) }}</span>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md leading-5 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Search...">
                        </div>

                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                                <span class="relative">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                                </span>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg">
                                <div class="rounded-md bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
                                    <div class="p-3 border-b border-gray-200 dark:border-gray-800">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                                            <button class="text-xs text-purple-600 dark:text-purple-400 hover:underline">Mark all as read</button>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-200 dark:divide-gray-800 max-h-64 overflow-y-auto">
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-150 ease-in-out">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <div :class="notification.read ? 'bg-gray-200 dark:bg-gray-700' : 'bg-purple-100 dark:bg-purple-900'" class="h-8 w-8 rounded-full flex items-center justify-center">
                                                            <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3 w-0 flex-1">
                                                        <p x-text="notification.title" :class="notification.read ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-100 font-medium'"></p>
                                                        <p x-text="notification.time" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                                    </div>
                                                    <div x-show="!notification.read" class="ml-2 flex-shrink-0">
                                                        <span class="inline-block h-2 w-2 rounded-full bg-purple-600 dark:bg-purple-400"></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                    <div class="p-3 border-t border-gray-200 dark:border-gray-800 text-center">
                                        <a href="#" class="text-sm text-purple-600 dark:text-purple-400 hover:underline">View all notifications</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button @click="toggleDarkMode" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                            <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</div>
