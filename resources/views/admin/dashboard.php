@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Themes Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Themes</h2>
            
            <!-- Active Theme -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Active Theme</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                    <p class="text-gray-900 dark:text-white">{{ $activeTheme }}</p>
                </div>
            </div>

            <!-- Available Themes -->
            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Available Themes</h3>
                <div class="space-y-2">
                    @foreach($themes as $theme)
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 rounded">
                        <span class="text-gray-900 dark:text-white">{{ $theme }}</span>
                        @if($theme !== $activeTheme)
                        <form action="{{ route('admin.themes.activate') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="theme" value="{{ $theme }}">
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90">
                                Activate
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Plugins Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Plugins</h2>
            
            <!-- Installed Plugins -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Installed Plugins</h3>
                <div class="space-y-2">
                    @foreach($plugins as $plugin)
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 rounded">
                        <span class="text-gray-900 dark:text-white">{{ $plugin }}</span>
                        <form action="{{ route('admin.plugins.uninstall') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="plugin" value="{{ $plugin }}">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                Uninstall
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Available Plugins -->
            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Available Plugins</h3>
                <div class="space-y-2">
                    @foreach($availablePlugins as $plugin)
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 rounded">
                        <span class="text-gray-900 dark:text-white">{{ $plugin }}</span>
                        <form action="{{ route('admin.plugins.install') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="plugin" value="{{ $plugin }}">
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90">
                                Install
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 