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
                    <div class="mb-6">
                        <a href="{{route('tasks.create')}}">
                            <x-primary-button>
                                Create Task
                            </x-primary-button>
                        </a>

                        @if ($projects->count() > 0)
                            <form method="GET" action="{{ route('tasks.index') }}" class="w-full sm:w-64 float-right mb-2">
                                <select id="project_id" name="project_id"
                                    class="block w-full px-2 py-2 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50
                                    focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer"
                                    onchange="this.form.submit()">
                                    @forelse($projects as $p)
                                        <option value="{{ $p->id }}" @selected($activeProjectId == $p->id)>
                                            {{ $p->name }}
                                        </option>
                                    @empty
                                        <option disabled>No projects yet</option>
                                    @endforelse
                                </select>
                            </form>
                        @else
                            <h2 class="text-base text-gray-900  dark:text-gray-100 mb-4 float-right">
                                No projects found. Please create a project to manage your tasks.
                            </h2>
                        @endif
                    </div>
                    <div class="js-list mt-4">
                         @foreach($tasks as $task)
                            <div data-id="{{ $task->id }}" class="is-idle js-item w-full shadow-lg bg-slate-100 dark:bg-gray-900 p-4 rounded-lg text-gray-900 dark:text-gray-100 mt-3 items-center relative will-change-transform">
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
