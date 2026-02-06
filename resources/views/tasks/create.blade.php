<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create New Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="title" value="Task Title" />
                                <x-text-input id="title" type="text" name="title" :value="old('title')" required />
                                <x-input-error :messages="$errors->get('title')" />
                            </div>
                            <div>
                                <x-input-label for="project" value="Project" />
                                <x-text-input id="project" type="text" name="project" :value="$activeProject->name ?? 'no project linked'" disabled />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="due_date" value="Due Date" />
                            <x-text-input id="due_date" type="date" name="due_date" :value="old('due_date')" required />
                            <x-input-error :messages="$errors->get('due_date')" />
                        </div>
                        <div class="mt-4">
                            <a href="{{route('tasks.index')}}">
                                <x-secondary-button type="button">Cancel</x-x-secondary-button>
                            </a>
                            <x-primary-button class="ms-2">
                                Create Task
                            </x-primary-button>
                        </div>
                    </form>
          
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
