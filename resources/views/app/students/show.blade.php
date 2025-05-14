<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $student->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('students.edit', $student->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Student
                </a>
                <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Students
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Student Profile -->
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-col items-center">
                                @if($student->profile_photo)
                                    <img src="{{ $student->profile_photo }}" alt="{{ $student->name }}" class="h-32 w-32 rounded-full object-cover mb-4">
                                @else
                                    <div class="h-32 w-32 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold text-4xl mb-4">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $student->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $student->email }}</p>
                                @if($student->student_id)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ID: {{ $student->student_id }}</p>
                                @endif
                            </div>

                            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Student Information</h4>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Plan:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white ml-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->plan == 'premium' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                                {{ ucfirst($student->plan) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Enrolled in:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white ml-1">{{ $student->subjects->count() }} subjects</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Joined:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white ml-1">{{ $student->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($student->notes)
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Notes:</span>
                                            <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $student->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Details -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex" x-data="{ activeTab: 'subjects' }">
                                <button @click="activeTab = 'subjects'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'subjects', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'subjects' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                    Enrolled Subjects
                                </button>
                                <button @click="activeTab = 'submissions'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'submissions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'submissions' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                    Activity Submissions
                                </button>
                            </nav>
                        </div>

                        <!-- Subjects Tab -->
                        <div x-show="activeTab === 'subjects'" class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Enrolled Subjects</h3>
                                <button type="button" onclick="document.getElementById('addSubjectsModal').classList.remove('hidden')" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Enroll in Subjects
                                </button>
                            </div>

                            @if($student->subjects->count() > 0)
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($student->subjects as $subject)
                                        <li class="py-4">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="w-2 h-10 rounded-l-md" style="background-color: {{ $subject->color }};"></div>
                                                    <div class="ml-3">
                                                        <h4 class="text-base font-medium text-gray-900 dark:text-white">{{ $subject->name }}</h4>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subject->code }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <a href="{{ route('subjects.show', $subject->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <form method="POST" action="{{ route('subjects.students.remove', [$subject->id, $student->id]) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure you want to remove this student from the subject?')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No subjects enrolled</h3>
                                    <p class="mt-1 text-gray-500 dark:text-gray-400">Enroll this student in subjects to get started.</p>
                                    <div class="mt-6">
                                        <button type="button" onclick="document.getElementById('addSubjectsModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Enroll in Subjects
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Submissions Tab -->
                        <div x-show="activeTab === 'submissions'" class="p-6" style="display: none;">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity Submissions</h3>

                            @php
                                $submissions = \App\Models\ActivitySubmission::where('student_id', $student->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if($submissions->count() > 0)
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($submissions as $submission)
                                        <li class="py-4">
                                            <a href="{{ route('submissions.show', $submission->id) }}" class="flex items-start hover:bg-gray-50 dark:hover:bg-gray-700 -mx-4 px-4 py-2 rounded-lg transition-colors">
                                                <div class="p-2 rounded-full bg-{{ $submission->status === 'graded' ? 'green' : 'yellow' }}-100 text-{{ $submission->status === 'graded' ? 'green' : 'yellow' }}-600 mr-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between">
                                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $submission->activity->title }}</h4>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $submission->created_at->format('M d, Y') }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $submission->activity->subject->name }}</p>
                                                    <div class="mt-2 flex items-center">
                                                        <span class="text-xs bg-{{ $submission->status === 'graded' ? 'green' : 'yellow' }}-100 text-{{ $submission->status === 'graded' ? 'green' : 'yellow' }}-800 px-2 py-0.5 rounded-full">
                                                            {{ ucfirst($submission->status) }}
                                                        </span>
                                                        @if($submission->status === 'graded')
                                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                                Score: {{ $submission->score }} / {{ $submission->activity->points ?? 'N/A' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No submissions yet</h3>
                                    <p class="mt-1 text-gray-500 dark:text-gray-400">This student hasn't submitted any work yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Subjects Modal -->
    <div id="addSubjectsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Enroll in Subjects</h3>
                    <button type="button" onclick="document.getElementById('addSubjectsModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('students.enroll', $student->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="subject_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Subjects</label>
                        <select name="subject_ids[]" id="subject_ids" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" multiple>
                            @php
                                $enrolledSubjectIds = $student->subjects->pluck('id')->toArray();
                                $availableSubjects = \App\Models\Subject::where('user_id', auth()->id())
                                    ->whereNotIn('id', $enrolledSubjectIds)
                                    ->orderBy('name')
                                    ->get();
                            @endphp
                            @foreach($availableSubjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @if($availableSubjects->count() === 0)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No more subjects available to enroll in. <a href="{{ route('subjects.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Create new subjects</a>.</p>
                        @endif
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="document.getElementById('addSubjectsModal').classList.add('hidden')" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Enroll
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
