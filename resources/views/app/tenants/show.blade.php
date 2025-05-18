<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mr-3">
                    <i class="fas fa-building text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Tenant Details') }}: <span class="text-indigo-600 dark:text-indigo-400">{{ $tenant->name }}</span>
                </h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tenants.edit', $tenant) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-xl hover:from-amber-600 hover:to-yellow-600 shadow-sm hover:shadow transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Tenant
                </a>
                <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-gray-500 to-slate-500 text-white rounded-xl hover:from-gray-600 hover:to-slate-600 shadow-sm hover:shadow transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Tenants
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center mb-4">
                                    <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                                    Basic Information
                                </h3>
                                <div class="space-y-5">
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Company Name</label>
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-sm">
                                                <span class="font-medium">{{ substr($tenant->name, 0, 2) }}</span>
                                            </div>
                                            <span class="ml-3 text-gray-900 dark:text-white text-lg font-medium">{{ $tenant->name }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email Address</label>
                                        <div class="flex items-center">
                                            <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-2">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="text-gray-900 dark:text-white">{{ $tenant->email }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Domain</label>
                                        <div class="flex items-center">
                                            <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-2">
                                                <i class="fas fa-globe"></i>
                                            </div>
                                            <span class="text-gray-900 dark:text-white">
                                                {{ $tenant->domains->first()->domain ?? 'No domain' }}.{{ config('app.domain') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                                        <div class="flex items-center">
                                            @if($tenant->active)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                    <i class="fas fa-check-circle mr-1.5"></i> Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                                    <i class="fas fa-times-circle mr-1.5"></i> Inactive
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center mb-4">
                                    <i class="fas fa-clock text-indigo-500 mr-2"></i>
                                    Timestamps
                                </h3>
                                <div class="space-y-5">
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created At</label>
                                        <div class="flex items-center">
                                            <div class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-2">
                                                <i class="fas fa-calendar-plus"></i>
                                            </div>
                                            <span class="text-gray-900 dark:text-white">{{ $tenant->created_at->format('F j, Y g:i A') }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</label>
                                        <div class="flex items-center">
                                            <div class="p-2 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-2">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <span class="text-gray-900 dark:text-white">{{ $tenant->updated_at->format('F j, Y g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center mb-4">
                                    <i class="fas fa-cogs text-indigo-500 mr-2"></i>
                                    Tenant Actions
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                                        <a href="{{ route('tenants.edit', $tenant) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-xl hover:from-amber-600 hover:to-yellow-600 shadow-sm hover:shadow transition-all duration-200 w-full sm:w-auto">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit Tenant
                                        </a>

                                        @if($tenant->active)
                                            <form action="{{ route('tenants.disable', $tenant) }}" method="POST" class="w-full sm:w-auto">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl hover:from-orange-600 hover:to-amber-600 shadow-sm hover:shadow transition-all duration-200">
                                                    <i class="fas fa-ban mr-2"></i>
                                                    Disable Tenant
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('tenants.enable', $tenant) }}" method="POST" class="w-full sm:w-auto">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 shadow-sm hover:shadow transition-all duration-200">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    Enable Tenant
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                        
                                    </div>
                                </div>
                            </div>
                                    <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 dark:bg-gray-700/30 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600/40 transition-colors">
                                        <i class="fas fa-list mr-1.5"></i>
                                        All Tenants
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>