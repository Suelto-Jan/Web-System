<aside class="w-64 bg-gradient-to-b from-indigo-700 via-indigo-600 to-blue-500 shadow-xl flex flex-col py-8 min-h-screen">
    <!-- User Avatar and Welcome -->
    <div class="flex flex-col items-center mb-8">
        <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center shadow-lg mb-2 border-4 border-indigo-300 overflow-hidden">
            @php
                $profilePhoto = Auth::guard('student')->user()->profile_photo ?? null;
            @endphp
            @if($profilePhoto)
                <img src="{{ $profilePhoto }}" alt="Avatar" class="object-cover h-full w-full">
            @else
                <span class="text-3xl font-bold text-indigo-600">{{ strtoupper(substr(Auth::guard('student')->user()->name ?? 'S', 0, 1)) }}</span>
            @endif
        </div>
        <div class="text-white text-lg font-semibold">Welcome,</div>
        <div class="text-indigo-100 text-base font-medium truncate max-w-[12rem]">{{ Auth::guard('student')->user()->name ?? 'Student' }}</div>
    </div>
    <nav class="flex flex-col space-y-1 px-4">
        <span class="text-xs text-indigo-200 uppercase tracking-wider mb-2">Main</span>
        <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-white font-semibold hover:bg-white/10 focus:bg-white/20 transition group">
            <i class="fas fa-home mr-3 text-indigo-200 group-hover:text-indigo-400"></i> Dashboard
        </a>
        <a href="{{ route('student.subjects') }}" class="flex items-center px-3 py-2 rounded-lg text-white font-semibold hover:bg-white/10 focus:bg-white/20 transition group">
            <i class="fas fa-book mr-3 text-indigo-200 group-hover:text-indigo-400"></i> My Subjects
        </a>
        <a href="{{ route('student.assignments') }}" class="flex items-center px-3 py-2 rounded-lg text-white font-semibold hover:bg-white/10 focus:bg-white/20 transition group">
            <i class="fas fa-tasks mr-3 text-indigo-200 group-hover:text-indigo-400"></i> Assignments
        </a>
        <div class="border-t border-indigo-400/30 my-4"></div>
        <span class="text-xs text-indigo-200 uppercase tracking-wider mb-2">Account</span>
        <a href="{{ route('student.settings.edit') }}" class="flex items-center px-3 py-2 rounded-lg text-white font-semibold hover:bg-white/10 focus:bg-white/20 transition group">
            <i class="fas fa-user mr-3 text-indigo-200 group-hover:text-indigo-400"></i> Profile
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2 rounded-lg text-white font-semibold hover:bg-red-600 focus:bg-red-700 transition group">
                <i class="fas fa-sign-out-alt mr-3 text-indigo-200 group-hover:text-white"></i> Logout
            </button>
        </form>
    </nav>
</aside> 