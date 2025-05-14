<div x-data="{ open: true }" class="h-screen flex flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-sm">
    <!-- Sidebar Header -->
    <div class="p-4 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <div class="h-8 w-8 rounded-full animated-gradient flex items-center justify-center">
                <span class="text-white font-bold">{{ substr(tenant('name') ?? config('app.name'), 0, 1) }}</span>
            </div>
            <span x-show="open" class="text-lg font-semibold text-gray-900 dark:text-white">{{ tenant('name') ?? config('app.name') }}</span>
        </a>
        <button @click="open = !open" class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
            <svg x-show="open" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="!open" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-2 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-home mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                <span x-show="open">Dashboard</span>
            </a>

            <!-- Subjects -->
            <a href="{{ route('subjects.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('subjects.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-book mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('subjects.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                <span x-show="open">Subjects</span>
            </a>

            <!-- Students -->
            <a href="{{ route('students.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('students.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-user-graduate mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('students.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                <span x-show="open">Students</span>
            </a>

            <!-- Activities -->
            <a href="{{ route('activities.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('activities.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-tasks mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('activities.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                <span x-show="open">Activities</span>
            </a>

            <!-- Submissions -->
            <a href="{{ route('activities.index', ['view' => 'submissions']) }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('activities.*') && request()->query('view') == 'submissions' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-clipboard-check mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('activities.*') && request()->query('view') == 'submissions' ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                <span x-show="open">Submissions</span>
            </a>

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700"></div>

            <!-- Settings -->
            @if(Auth::guard('student')->check())
                <a href="{{ route('student.settings.edit') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('student.settings.edit') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-cog mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('student.settings.edit') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                    <span x-show="open">Settings</span>
                </a>
            @else
                <a href="{{ route('tenant.settings.edit') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('tenant.settings.edit') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-cog mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('tenant.settings.edit') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                    <span x-show="open">Settings</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div x-show="open" class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-300">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</p>
                @if(Auth::guard('student')->check())
                    <a href="{{ route('student.settings.edit') }}" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400">Settings</a>
                @else
                    <a href="{{ route('tenant.settings.edit') }}" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400">Settings</a>
                @endif
            </div>
        </div>
        <div x-show="!open" class="flex justify-center">
            <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-300">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </div>
    </div>
</div>
