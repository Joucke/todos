<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['title' => 'my groups', 'url' => route('groups.index'), 'icon' => 'group'],
            ['title' => 'add a group']
        ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Group Details</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a group to organize your tasks and collaborate with others.</p>
                        </div>

                        <form method="POST" action="{{ route('groups.store') }}" class="space-y-6">
                            @csrf

                            <div>
                        <label for="name" class="form-label">Group Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="form-input @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            value="{{ old('name') }}"
                            autocomplete="off"
                            data-1p-ignore
                            required
                            autofocus
                            placeholder="Enter a name for your group"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Choose a descriptive name that helps identify the purpose of this group.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('groups.index') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-gray-100 font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <x-icon type="add" size="md" class="mr-2" />
                            Add Group
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="mt-8 lg:mt-0">
            <div class="space-y-6">
            <!-- What happens next info -->
            <x-sidebar-box type="info" title="What happens next?">
                <ul class="list-disc list-outside ml-4 space-y-1 max-w-none">
                    <li>You'll become the owner of this group</li>
                    <li>You can invite others via email</li>
                    <li>You can add task lists to organize work</li>
                    <li>Members can view and complete tasks</li>
                </ul>
            </x-sidebar-box>
            </div>
        </div>
    </div>
</x-app-layout>
