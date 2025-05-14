<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Assignments') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Active Assignments -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Active Assignments</h3>
                <div class="grid grid-cols-1 gap-6">
                    @forelse($activeActivities as $activity)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden card-hover border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $activity->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $activity->description }}</p>
                                        @if($activity->attachment)
                                            <div class="mt-2">
                                                @if(Auth::guard('student')->check())
                                                    <a href="{{ route('activities.download-attachment', ['path' => $activity->attachment]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-100 text-indigo-800 rounded-md hover:bg-indigo-200 transition-colors text-sm">
                                                        <i class="fas fa-paperclip mr-1.5"></i>
                                                        Download Attachment
                                                    </a>
                                                @else
                                                    <a href="{{ route('activities.download-attachment.tenant', ['path' => $activity->attachment]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-100 text-indigo-800 rounded-md hover:bg-indigo-200 transition-colors text-sm">
                                                        <i class="fas fa-paperclip mr-1.5"></i>
                                                        Download Attachment
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            <i class="fas fa-clock mr-1.5"></i>
                                            Due: {{ $activity->due_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Submission Form -->
                                <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                            <i class="fas fa-upload mr-1.5"></i>
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                            No active assignments at the moment.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Submitted Assignments -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Submitted Assignments</h3>
                <div class="grid grid-cols-1 gap-6">
                    @forelse($submissions as $submission)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden card-hover border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $submission->activity->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $submission->activity->description }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($submission->grade)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                <i class="fas fa-star mr-1.5"></i>
                                                Grade: {{ $submission->grade }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            <i class="fas fa-clock mr-1.5"></i>
                                            Submitted: {{ $submission->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>

                                @if($submission->feedback)
                                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <h5 class="font-medium text-gray-900 dark:text-white mb-2">Feedback</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $submission->feedback }}</p>
                                    </div>
                                @endif

                                <div class="mt-4 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-gray-400 mr-1.5"></i>
                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ basename($submission->file_path) }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="openPdfViewer('{{ Storage::url($submission->file_path) }}')" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                            <i class="fas fa-eye mr-1.5"></i>
                                            Preview
                                        </button>
                                        <a href="{{ Storage::url($submission->file_path) }}" download class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-sm shadow-sm">
                                            <i class="fas fa-download mr-1.5"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                            You have not submitted any assignments yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Viewer Modal -->
    <div id="pdfViewerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-11/12 max-w-4xl h-5/6 flex flex-col">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Document Preview</h3>
                <button onclick="closePdfViewer()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 p-4 overflow-auto">
                <iframe id="pdfViewer" class="w-full h-full" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openPdfViewer(url) {
            document.getElementById('pdfViewerModal').classList.remove('hidden');
            document.getElementById('pdfViewerModal').classList.add('flex');
            document.getElementById('pdfViewer').src = url;
        }

        function closePdfViewer() {
            document.getElementById('pdfViewerModal').classList.add('hidden');
            document.getElementById('pdfViewerModal').classList.remove('flex');
            document.getElementById('pdfViewer').src = '';
        }
    </script>
    @endpush
</x-tenant-app-layout> 