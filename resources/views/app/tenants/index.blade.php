<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mr-3">
                    <i class="fas fa-building text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Tenant Management') }}
                </h2>
            </div>
            
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 rounded-xl shadow-sm flex items-start" role="alert">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3 text-lg"></i>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-4 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput" placeholder="Search tenants..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="statusFilter" class="block w-full pl-3 pr-10 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <button class="p-2 rounded-lg text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-list text-lg"></i>
                            </button>
                            <button class="p-2 rounded-lg text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-th-large text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenants Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <span>Company Name</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Domain</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($tenants as $tenant)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-sm">
                                                        <span class="font-medium">{{ substr($tenant->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $tenant->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                                {{ $tenant->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                                <i class="fas fa-globe text-gray-400 mr-2"></i>
                                                {{ $tenant->domains->first()->domain ?? 'No domain' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tenant->active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                    <i class="fas fa-check-circle mr-1.5"></i> Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                                    <i class="fas fa-times-circle mr-1.5"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                                {{ $tenant->created_at->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('tenants.show', $tenant) }}" class="p-1.5 rounded-lg text-indigo-600 hover:text-white dark:text-indigo-400 dark:hover:text-white hover:bg-indigo-600 dark:hover:bg-indigo-600 transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tenants.edit', $tenant) }}" class="p-1.5 rounded-lg text-amber-600 hover:text-white dark:text-amber-400 dark:hover:text-white hover:bg-amber-600 dark:hover:bg-amber-600 transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($tenant->active)
                                                    <form action="{{ route('tenants.disable', $tenant) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 rounded-lg text-orange-600 hover:text-white dark:text-orange-400 dark:hover:text-white hover:bg-orange-600 dark:hover:bg-orange-600 transition-colors" title="Disable">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('tenants.enable', $tenant) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 rounded-lg text-green-600 hover:text-white dark:text-green-400 dark:hover:text-white hover:bg-green-600 dark:hover:bg-green-600 transition-colors" title="Enable">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg text-red-600 hover:text-white dark:text-red-400 dark:hover:text-white hover:bg-red-600 dark:hover:bg-red-600 transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this tenant?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Empty State (will only show if there are no tenants) -->
            @if($tenants->isEmpty())
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-8">
                <div class="text-center">
                    <i class="fas fa-building text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No tenants found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first tenant.</p>
                    <a href="{{ route('tenants.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create Tenant
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Add filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');

            if (searchInput && statusFilter) {
                const tableRows = document.querySelectorAll('tbody tr');

                const applyFilters = () => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value.toLowerCase();

                    tableRows.forEach(row => {
                        const companyName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                        const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const domain = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                        const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                        const matchesSearch = companyName.includes(searchTerm) ||
                                             email.includes(searchTerm) ||
                                             domain.includes(searchTerm);
                        const matchesStatus = statusValue === '' || status.includes(statusValue);

                        if (matchesSearch && matchesStatus) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                };

                searchInput.addEventListener('input', applyFilters);
                statusFilter.addEventListener('change', applyFilters);
            }
        });
    </script>
</x-app-layout>