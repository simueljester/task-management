<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Project List
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{route('projects.create')}}">
                        <x-primary-button  >
                            Create Project
                        </x-primary-button>
                    </a>

                    <div class="mt-2">
                        @forelse ($projects as $project)
                            <div class="p-4 border border-gray-200 dark:border-gray-700 mb-2 bg-white dark:bg-gray-900 rounded-xl text-gray-900 dark:text-gray-100">
                                <h3 class="font-semibold text-lg capitalize">{{ $project->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->tasks->count() }} tasks</p>

                                <div class="mt-3 flex items-center gap-4">
                                    <a href="{{ route('projects.edit', $project->id) }}" class="text-blue-500 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
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
                            </div>
                        @empty
                            No current project created
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
