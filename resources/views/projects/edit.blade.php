<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Project
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="name" value="Project Name" />
                            <x-text-input id="name" type="text" name="name" :value="old('name', $project->name)" required />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        <div class="mt-4">
                            <a href="{{route('projects.index')}}">
                                <x-secondary-button type="button">Cancel</x-x-secondary-button>
                            </a>
                            <x-primary-button class="ms-2">
                                Save Changes
                            </x-primary-button>
                        </div>
                    </form>
          
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
