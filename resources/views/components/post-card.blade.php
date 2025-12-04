@props(['post'])

<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300 flex flex-col h-full relative group">
    <div class="p-6 flex-1">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-1">
                    <a href="{{ route('posts.show', $post) }}" class="hover:underline decoration-blue-500 decoration-2">
                        {{ $post->title }}
                    </a>
                </h3>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    <span>Author: {{ $post->user->name }}</span> •
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </div>
                @if ($post->updated_at->gt($post->created_at))
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span>Updated: {{ $post->updated_at->diffForHumans() }}</span>
                    </div>
                @endif
            </div>

            @if (auth()->id() === $post->user_id)
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('posts.edit', $post) }}" class="text-yellow-500 hover:text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>

                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                        onsubmit="return confirm('Are you sure to delete?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed mb-4">
            {{ Str::limit($post->content, 150) }}
        </p>
    </div>

    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600">
        <span class="text-xs text-gray-500 dark:text-gray-300 flex items-center gap-1">
            💬 {{ $post->comments->count() }} Yorum
        </span>
    </div>
</div>
