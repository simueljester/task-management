<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Task List
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{route('tasks.create')}}">
                        <x-primary-button  >
                            Create Task
                        </x-primary-button>
                    </a>

                    <div class="js-list mt-2">
                         @foreach($tasks as $task)
                            <div data-id="{{ $task->id }}" class="is-idle js-item w-full bg-white dark:bg-gray-900 p-4 rounded-lg text-gray-900 dark:text-gray-100 mt-3 items-center relative will-change-transform">
                                <div class="drag-handle js-drag-handle"></div>
                                <p class="text-xl"> {{ $task->title }} </p>
                                <div class="mt-3 flex items-center gap-4">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-500 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-red-500 hover:underline"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                                    Due Date: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }} | Status: {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
