@php
    use Illuminate\Support\Facades\Auth;
@endphp
<aside x-data="{ open: true, activeMenu: '{{ request()->route()->getName() }}' }" class="w-64 bg-white dark:bg-gray-800 shadow-md flex flex-col border-r border-gray-100 dark:border-gray-700 relative h-full">
    <!-- Toggle Button -->
    <button @click="open = !open" class="absolute -right-3 top-20 bg-white dark:bg-gray-800 rounded-full p-1.5 shadow-md border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 z-10 transition-transform duration-300" :class="{'rotate-180': !open}">
        <i class="fas fa-chevron-left text-xs text-blue-500 dark:text-blue-400"></i>
    </button>

    <!-- Logo and Brand -->
    <div class="flex items-center py-4 px-4 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                <span class="text-lg font-bold text-white">{{ substr(tenant('name') ?? config('app.name'), 0, 1) }}</span>
            </div>
            <div x-show="open" class="transition-all duration-300 ease-in-out" x-transition>
                <div class="text-gray-800 dark:text-white text-lg font-bold">{{ tenant('name') ?? config('app.name') }}</div>
                <div class="text-gray-500 dark:text-gray-400 text-xs">Classroom</div>
                <div class="text-xs text-blue-600 dark:text-blue-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                    </svg>
                    <span>Teacher Portal</span>
                </div>
            </div>
        </div>
    </div>



    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3">
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'dashboard' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-home" :class="activeMenu === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Dashboard</span>
            </a>

            <a href="{{ route('subjects.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'subjects.index' || activeMenu.startsWith('subjects.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-book" :class="activeMenu === 'subjects.index' || activeMenu.startsWith('subjects.') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Subjects</span>
            </a>

            <a href="{{ route('students.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'students.index' || activeMenu.startsWith('students.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-user-graduate" :class="activeMenu === 'students.index' || activeMenu.startsWith('students.') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Students</span>
            </a>

            <a href="{{ route('activities.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'activities.index' || activeMenu.startsWith('activities.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-tasks" :class="activeMenu === 'activities.index' || activeMenu.startsWith('activities.') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Activities</span>
            </a>

            <a href="{{ route('submissions.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'submissions.index' || activeMenu.startsWith('submissions.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-clipboard-check" :class="activeMenu === 'submissions.index' || activeMenu.startsWith('submissions.') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Submissions</span>
            </a>

            <a href="{{ route('teacher-quizzes.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'teacher-quizzes.index' || activeMenu.startsWith('teacher-quizzes.') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-question-circle" :class="activeMenu === 'teacher-quizzes.index' || activeMenu.startsWith('teacher-quizzes.') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Quizzes</span>
            </a>

            <a href="{{ route('chat.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'chat.index' || activeMenu === 'chat.show' || activeMenu === 'chat.create' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-blue-600 dark:hover:text-blue-400'">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-comments" :class="activeMenu === 'chat.index' || activeMenu === 'chat.show' || activeMenu === 'chat.create' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400'"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Chat</span>
            </a>
        </div>

    
    </nav>

    <!-- Version Info and Logout -->
    <div class="mt-auto px-4 py-4 border-t border-gray-100 dark:border-gray-700">
        <div x-show="open" class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
            <div class="flex items-center">
                <div class="w-2 h-2 rounded-full bg-green-400 mr-2"></div>
                <span>Classroom v1.0</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                <div class="flex items-center justify-center w-8 h-8">
                    <i class="fas fa-sign-out-alt text-gray-500 dark:text-gray-400 group-hover:text-red-500"></i>
                </div>
                <span x-show="open" class="ml-3">Logout</span>
            </button>
        </form>
    </div>
</aside>
