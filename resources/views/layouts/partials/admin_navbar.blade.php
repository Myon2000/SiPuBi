<nav class="bg-blue-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <span class="text-xl font-bold">SiPuBi Admin</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.fertilizers.index') }}" 
                   class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('admin.fertilizers.*') ? 'bg-blue-700' : '' }}">
                    Pupuk
                </a>
                <a href="{{ route('admin.quotas.index') }}" 
                   class="hover:bg-blue-700 px-3 py-2 rounded {{ request()->routeIs('admin.quotas.*') ? 'bg-blue-700' : '' }}">
                    Kuota
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:bg-blue-700 px-3 py-2 rounded">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>