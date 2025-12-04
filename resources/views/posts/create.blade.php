<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create a new post') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition px-2 py-2 font-medium">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('posts.store') }}" class="flex flex-col gap-6">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="title" class="font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            placeholder="Enter a catchy title..." 
                            value="{{ old('title') }}"
                            class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm w-full"
                        >
                        @error('title')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="content" class="font-medium text-gray-700 dark:text-gray-300">Content</label>
                        <textarea 
                            name="content" 
                            id="content"
                            rows="8"
                            placeholder="Share your thoughts..." 
                            class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm w-full"
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-4">    
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 text-sm">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white rounded-lg px-6 py-2.5 font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition shadow-lg">
                            Publish Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>