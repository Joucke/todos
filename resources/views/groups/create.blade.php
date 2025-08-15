<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Group') }}
            </h2>
            <a href="{{ route('groups.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Groups
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-medium text-gray-900">Group Details</h3>
                    <p class="mt-1 text-sm text-gray-600">Create a new group to organize your tasks and collaborate with others.</p>
                </div>

                <form method="POST" action="{{ route('groups.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="form-label">Group Name</label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            class="form-input @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            value="{{ old('title') }}"
                            required
                            autofocus
                            placeholder="Enter a name for your group"
                        >
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Choose a descriptive name that helps identify the purpose of this group.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">What happens next?</h4>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>You'll become the owner of this group</li>
                                        <li>You can invite others via email</li>
                                        <li>You can create task lists to organize work</li>
                                        <li>Members can view and complete tasks</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('groups.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
