<x-app-layout>
   <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('posts.create') }}" class="text-blue-600 hover:text-gray-700 dark:hover:text-gray-200 transition px-2 py-2 font-medium">
                + New Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($posts as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="col-span-3 text-center py-10 text-gray-500 dark:text-gray-400">
                        There are no posts yet. Be the first to write!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
