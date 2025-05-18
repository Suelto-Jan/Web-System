<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Classroom Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                    <i class="fas fa-calendar-alt mr-1.5"></i> {{ now()->format('F j, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-4rem)] overflow-hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full overflow-y-auto pb-4">
            <!-- Welcome Banner -->
            <div class="animated-gradient rounded-xl shadow-lg mb-6 overflow-hidden">
                <div class="px-6 py-6 md:px-8 md:py-8 bg-black/20">
                    <div class="max-w-3xl">
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Welcome to Your Classroom</h2>
                        <p class="text-white/80 mb-4 text-sm md:text-base">Manage your subjects, students, and activities all in one place.</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-indigo-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-book mr-1.5"></i>
                                Subjects
                            </a>
                            <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-indigo-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-plus mr-1.5"></i>
                                New Subject
                            </a>
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-green-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-user-graduate mr-1.5"></i>
                                Students
                            </a>
                            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-purple-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-tasks mr-1.5"></i>
                                Activities
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                @php
                    $subjectCount = \App\Models\Subject::where('user_id', auth()->id())->count();
                    $studentCount = \App\Models\Student::count();
                    $activityCount = \App\Models\Activity::whereIn('subject_id',
                        \App\Models\Subject::where('user_id', auth()->id())->pluck('id')
                    )->count();
                    $submissionCount = \App\Models\ActivitySubmission::whereIn('activity_id',
                        \App\Models\Activity::whereIn('subject_id',
                            \App\Models\Subject::where('user_id', auth()->id())->pluck('id')
                        )->pluck('id')
                    )->count();
                @endphp

                <!-- Subject Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mr-3">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Subjects</p>
                            <div class="flex items-end">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $subjectCount }}</h3>
                                <a href="{{ route('subjects.index') }}" class="ml-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-xs">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-3">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Students</p>
                            <div class="flex items-end">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $studentCount }}</h3>
                                <a href="{{ route('students.index') }}" class="ml-2 text-green-600 hover:text-green-800 dark:text-green-400 text-xs">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Activities</p>
                            <div class="flex items-end">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $activityCount }}</h3>
                                <a href="{{ route('activities.index') }}" class="ml-2 text-purple-600 hover:text-purple-800 dark:text-purple-400 text-xs">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submissions Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-3">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Submissions</p>
                            <div class="flex items-end">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $submissionCount }}</h3>
                                <a href="{{ route('activities.index', ['view' => 'submissions']) }}" class="ml-2 text-amber-600 hover:text-amber-800 dark:text-amber-400 text-xs">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Section -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Recent Subjects</h3>
                </div>
                <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-xs shadow-sm">
                    <i class="fas fa-plus mr-1.5"></i>
                    Add Subject
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                @php
                    $subjects = \App\Models\Subject::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                @endphp

                @forelse($subjects as $subject)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all border border-gray-100 dark:border-gray-700">
                        <div class="h-20 relative" style="background-color: {{ $subject->color }};">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent flex flex-col justify-end p-3">
                                <h4 class="font-semibold text-sm text-white">{{ $subject->name }}</h4>
                                <p class="text-xs text-gray-200">{{ $subject->code }}</p>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="flex justify-between items-center mb-2 text-xs">
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate text-gray-400 mr-1"></i>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $subject->students->count() }} students</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tasks text-gray-400 mr-1"></i>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $subject->activities->count() }} activities</span>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ route('subjects.show', $subject->id) }}" class="flex-1 inline-flex items-center justify-center px-2 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-xs shadow-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    View
                                </a>
                                <a href="{{ route('activities.create', ['subject_id' => $subject->id]) }}" class="flex-1 inline-flex items-center justify-center px-2 py-1 bg-white border border-gray-200 dark:border-gray-700 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-xs">
                                    <i class="fas fa-plus mr-1"></i>
                                    Activity
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 mx-auto bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-book text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">No subjects yet</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto">Get started by creating your first subject.</p>
                        <div class="mt-3">
                            <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-xs">
                                <i class="fas fa-plus mr-1.5"></i>
                                Create Subject
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Recent Activities Section -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Recent Activities</h3>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-xs shadow-sm">
                        <i class="fas fa-plus mr-1.5"></i>
                        Add
                    </a>
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center px-2 py-1.5 bg-white border border-gray-200 dark:border-gray-700 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-xs">
                        All
                        <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                @php
                    $recentActivities = \App\Models\Activity::whereIn('subject_id',
                        \App\Models\Subject::where('user_id', auth()->id())->pluck('id')
                    )
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
                @endphp

                @if($recentActivities->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentActivities as $activity)
                            <li class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <a href="{{ route('activities.show', $activity->id) }}" class="flex items-start p-3">
                                    <div class="p-2 rounded-lg bg-{{ $activity->is_published ? 'green' : 'gray' }}-100 dark:bg-{{ $activity->is_published ? 'green' : 'gray' }}-900/30 text-{{ $activity->is_published ? 'green' : 'gray' }}-600 dark:text-{{ $activity->is_published ? 'green' : 'gray' }}-400 mr-3 flex-shrink-0">
                                        <i class="fas fa-{{ $activity->type === 'assignment' ? 'clipboard-list' : 'book-open' }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-sm text-gray-900 dark:text-white truncate pr-2">{{ $activity->title }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap flex-shrink-0">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center flex-wrap gap-1">
                                            <span class="inline-flex items-center text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 px-1.5 py-0.5 rounded-full">
                                                {{ $activity->subject->name }}
                                            </span>
                                            <span class="inline-flex items-center text-xs bg-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-100 dark:bg-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-900/30 text-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-800 dark:text-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-300 px-1.5 py-0.5 rounded-full">
                                                {{ ucfirst($activity->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 self-center">
                                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-4 text-center">
                        <div class="w-10 h-10 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-tasks text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">No activities yet</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto">Create activities for your subjects.</p>
                        <div class="mt-3">
                            <a href="{{ route('activities.create') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-sm text-xs">
                                <i class="fas fa-plus mr-1.5"></i>
                                Create Activity
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Quick Actions</h3>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <!-- Create Subject -->
                <a href="{{ route('subjects.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                    <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mr-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-medium text-sm text-gray-900 dark:text-white">New Subject</h4>
                    </div>
                </a>

                <!-- Add Student -->
                <a href="{{ route('students.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                    <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-medium text-sm text-gray-900 dark:text-white">Add Student</h4>
                    </div>
                </a>

                <!-- Create Activity -->
                <a href="{{ route('activities.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                    <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-medium text-sm text-gray-900 dark:text-white">New Activity</h4>
                    </div>
                </a>

                <!-- View Submissions -->
                <a href="{{ route('activities.index', ['view' => 'submissions']) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                    <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-medium text-sm text-gray-900 dark:text-white">Submissions</h4>
                    </div>
                </a>
            </div>

            <!-- Students Section -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Recent Students</h3>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('students.create') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs shadow-sm">
                        <i class="fas fa-plus mr-1.5"></i>
                        Add
                    </a>
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-2 py-1.5 bg-white border border-gray-200 dark:border-gray-700 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-xs">
                        All
                        <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                @php
                    $recentStudents = \App\Models\Student::orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                @endphp

                @if($recentStudents->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentStudents as $student)
                            <li class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <a href="{{ route('students.show', $student->id) }}" class="p-3 flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($student->profile_photo)
                                            <img src="{{ Storage::url($student->profile_photo) }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover border border-white dark:border-gray-700 shadow-sm">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 font-semibold border border-white dark:border-gray-700 shadow-sm">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-medium text-sm text-gray-900 dark:text-white truncate">{{ $student->name }}</h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 whitespace-nowrap">
                                                {{ $student->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-xs text-gray-600 dark:text-gray-300 truncate">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0">
                                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-4 text-center">
                        <div class="w-10 h-10 mx-auto bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-2">
                            <i class="fas fa-user-graduate text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">No students yet</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto">Add students to your classroom.</p>
                        <div class="mt-3">
                            <a href="{{ route('students.create') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm text-xs">
                                <i class="fas fa-plus mr-1.5"></i>
                                Add Student
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-tenant-app-layout>
