<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $post->title }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 transition">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span>Author: {{ $post->user->name }}</span> •
                        <span>{{ $post->created_at->format('d M Y, H:i') }}</span>

                        @if ($post->updated_at->gt($post->created_at))
                            <span class="ml-2 text-xs">(Edited)</span>
                        @endif
                    </div>
                </div>

                <p class="text-gray-900 dark:text-gray-100 text-lg leading-relaxed whitespace-pre-line">
                    {{ $post->content }}
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Leave a Comment</h3>

                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">

                    <textarea name="message" rows="3" placeholder="What do you think?"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                        required></textarea>

                    <div class="flex justify-end mt-2">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Post Comment
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 px-2">
                    Comments ({{ $post->comments->count() }})
                </h3>

                @forelse($post->comments as $comment)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex gap-4 mb-2">
                        <div
                            class="flex-shrink-0 w-10 h-10 px-2 py-2 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-600 dark:text-gray-300">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>

                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-gray-900 dark:text-gray-100">
                                    {{ $comment->user->name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-gray-700 dark:text-gray-300 text-sm">
                                {{ $comment->message }}
                            </p>

                            @if (auth()->id() === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                    class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs hover:underline">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">No comments yet. Be the first!</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
