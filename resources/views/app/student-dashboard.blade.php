<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Student Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                    <i class="fas fa-calendar-alt mr-1.5"></i> {{ now()->format('F j, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="animated-gradient rounded-xl shadow-lg mb-8 overflow-hidden">
                <div class="px-6 py-8 md:px-10 md:py-12 bg-black/20">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Welcome, {{ Auth::guard('student')->user()->name ?? 'Student' }}</h2>
                    <p class="text-white/80 mb-6 text-lg">Here are your enrolled classes.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($subjects as $subject)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden card-hover border border-gray-100 dark:border-gray-700">
                        <div class="h-32 relative" style="background-color: {{ $subject->color ?? '#4f46e5' }};">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent flex flex-col justify-end p-4">
                                <h4 class="font-semibold text-lg text-white">{{ $subject->name }}</h4>
                                <p class="text-sm text-gray-200">{{ $subject->code }}</p>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-tasks text-gray-400 mr-1.5"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $subject->activities->count() }} activities</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="#" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                    <i class="fas fa-eye mr-1.5"></i>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                        You are not enrolled in any classes yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-tenant-app-layout> 