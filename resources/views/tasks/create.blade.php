<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['title' => 'my groups', 'url' => route('groups.index'), 'icon' => 'group'],
            ['title' => $group->name, 'url' => route('groups.show', $group)],
            ['title' => $taskList->name, 'url' => route('groups.show', ['group' => $group, 'list' => $taskList->id])],
            ['title' => 'add a task']
        ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Task Details</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a task to {{ $taskList->name }} in {{ $group->name }}.</p>
                        </div>

                        <form method="POST" action="{{ route('task-lists.tasks.store', $taskList) }}" class="space-y-6">
                            @csrf

                            <!-- Task Name Field -->
                            <div>
                                <label for="name" class="form-label">Task Name</label>
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
                                    placeholder="Enter a name for your task"
                                >
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Choose a clear, actionable name that describes what needs to be done.</p>
                            </div>

                            <!-- Interval Field -->
                            <!-- Interval Field -->
                            <div>
                                <label class="form-label">Repeat Every</label>
                                <div class="space-y-3 mt-2">
                                    <!-- Preset Values -->
                                    <div class="flex flex-wrap gap-2">
                                        @foreach([
                                            1 => '1d',
                                            2 => '2d',
                                            3 => '3d',
                                            4 => '4d',
                                            5 => '5d',
                                            6 => '6d',
                                            7 => '1w',
                                            10 => '10d',
                                            14 => '2w',
                                            21 => '3w',
                                            28 => '4w'
                                        ] as $value => $label)
                                            <label class="relative">
                                                <input
                                                    type="radio"
                                                    name="interval_type"
                                                    value="preset"
                                                    data-interval="{{ $value }}"
                                                    {{ old('interval', 1) == $value ? 'checked' : '' }}
                                                    class="sr-only peer interval-preset"
                                                >
                                                <div class="px-3 py-2 text-sm font-medium rounded-lg border-2 cursor-pointer transition-all duration-200
                                                    peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white
                                                    border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                                                    hover:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700
                                                    peer-checked:hover:bg-indigo-700">
                                                    {{ $label }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <!-- Custom Input Option -->
                                    <div class="flex items-center space-x-3">
                                        <label class="relative">
                                            <input
                                                type="radio"
                                                name="interval_type"
                                                value="custom"
                                                {{ !in_array(old('interval', 1), [1,2,3,4,5,6,7,10,14,21,28]) ? 'checked' : '' }}
                                                class="sr-only peer"
                                                id="custom-interval-radio"
                                            >
                                            <div class="px-3 py-2 text-sm font-medium rounded-lg border-2 cursor-pointer transition-all duration-200
                                                peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white
                                                border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                                                hover:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700
                                                peer-checked:hover:bg-indigo-700">
                                                Custom
                                            </div>
                                        </label>
                                        <input
                                            type="number"
                                            id="custom-interval-input"
                                            min="1"
                                            max="365"
                                            class="form-input w-20 @error('interval') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            value="{{ !in_array(old('interval', 1), [1,2,3,4,5,6,7,10,14,21,28]) ? old('interval', '') : '' }}"
                                            placeholder="30"
                                        >
                                        <span class="text-sm text-gray-600 dark:text-gray-400">days</span>
                                    </div>
                                </div>

                                <!-- Hidden input that will be submitted -->
                                <input type="hidden" name="interval" id="interval-hidden" value="{{ old('interval', 1) }}">

                                @error('interval')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">How often this task should be repeated.</p>
                            </div>

                            <!-- Days Field (only shown for weekly tasks) -->
                            <div id="days-field" class="hidden">
                                <label class="form-label">Days of the Week</label>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach([
                                        'monday' => 'Mon',
                                        'tuesday' => 'Tue',
                                        'wednesday' => 'Wed',
                                        'thursday' => 'Thu',
                                        'friday' => 'Fri',
                                        'saturday' => 'Sat',
                                        'sunday' => 'Sun'
                                    ] as $day => $abbrev)
                                        <label class="relative">
                                            <input
                                                type="checkbox"
                                                name="days[]"
                                                value="{{ $day }}"
                                                {{ in_array($day, old('days', [])) ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <div class="px-4 py-2 text-sm font-medium rounded-lg border-2 cursor-pointer transition-all duration-200
                                                peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white
                                                border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                                                hover:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700
                                                peer-checked:hover:bg-indigo-700">
                                                {{ $abbrev }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('days')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Select which days of the week this task should be scheduled.</p>
                            </div>                            <!-- Days Field -->
                            <div>
                                <label class="form-label">Days of the Week</label>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach([
                                        'monday' => 'Mon',
                                        'tuesday' => 'Tue',
                                        'wednesday' => 'Wed',
                                        'thursday' => 'Thu',
                                        'friday' => 'Fri',
                                        'saturday' => 'Sat',
                                        'sunday' => 'Sun'
                                    ] as $day => $abbrev)
                                        <label class="relative">
                                            <input
                                                type="checkbox"
                                                name="days[]"
                                                value="{{ $day }}"
                                                {{ in_array($day, old('days', [])) ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <div class="px-4 py-2 text-sm font-medium rounded-lg border-2 cursor-pointer transition-all duration-200
                                                peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white
                                                border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                                                hover:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700
                                                peer-checked:hover:bg-indigo-700">
                                                {{ $abbrev }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('days')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Select which days of the week this task should be scheduled.</p>
                            </div>

                            <!-- Optional Field -->
                            <div class="flex items-center space-x-3">
                                <input
                                    type="checkbox"
                                    id="optional"
                                    name="optional"
                                    value="1"
                                    {{ old('optional') ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700"
                                >
                                <label for="optional" class="text-sm text-gray-700 dark:text-gray-300">
                                    This task is optional
                                </label>
                            </div>

                            <!-- Start and End Date Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Start Date Field -->
                                <div>
                                    <label for="starts_at" class="form-label">Start Date (optional)</label>
                                    <input
                                        id="starts_at"
                                        name="starts_at"
                                        type="date"
                                        class="form-input @error('starts_at') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                        value="{{ old('starts_at') }}"
                                    >
                                    @error('starts_at')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">When this task should start being scheduled.</p>
                                </div>

                                <!-- End Date Field -->
                                <div>
                                    <label for="ends_at" class="form-label">End Date (optional)</label>
                                    <input
                                        id="ends_at"
                                        name="ends_at"
                                        type="date"
                                        class="form-input @error('ends_at') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                        value="{{ old('ends_at') }}"
                                    >
                                    @error('ends_at')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">When this task should stop being scheduled.</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('groups.show', ['group' => $taskList->group, 'list' => $taskList->id]) }}"
                                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    Add Task
                                </button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const hiddenInterval = document.getElementById('interval-hidden');
                                const customInput = document.getElementById('custom-interval-input');
                                const customRadio = document.getElementById('custom-interval-radio');
                                const presetRadios = document.querySelectorAll('.interval-preset');
                                const daysField = document.getElementById('days-field');

                                // Function to toggle days field visibility
                                function toggleDaysField(intervalValue) {
                                    if (parseInt(intervalValue) === 7) {
                                        daysField.classList.remove('hidden');
                                    } else {
                                        daysField.classList.add('hidden');
                                        // Clear all day selections when hiding
                                        const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
                                        dayCheckboxes.forEach(checkbox => {
                                            checkbox.checked = false;
                                        });
                                    }
                                }

                                // Handle preset button clicks
                                presetRadios.forEach(radio => {
                                    radio.addEventListener('change', function() {
                                        if (this.checked) {
                                            const intervalValue = this.getAttribute('data-interval');
                                            hiddenInterval.value = intervalValue;
                                            customInput.value = '';

                                            toggleDaysField(intervalValue);

                                            // Auto-select all days for daily tasks
                                            if (parseInt(intervalValue) === 1) {
                                                const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
                                                dayCheckboxes.forEach(checkbox => {
                                                    checkbox.checked = true;
                                                });
                                            }
                                        }
                                    });
                                });

                                // Handle custom input
                                customInput.addEventListener('input', function() {
                                    if (this.value) {
                                        customRadio.checked = true;
                                        hiddenInterval.value = this.value;

                                        toggleDaysField(this.value);

                                        // Auto-select all days for daily tasks
                                        if (parseInt(this.value) === 1) {
                                            const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
                                            dayCheckboxes.forEach(checkbox => {
                                                checkbox.checked = true;
                                            });
                                        }
                                    }
                                });

                                // Focus custom input when custom radio is clicked
                                customRadio.addEventListener('change', function() {
                                    if (this.checked) {
                                        customInput.focus();
                                        if (customInput.value) {
                                            hiddenInterval.value = customInput.value;
                                            toggleDaysField(customInput.value);
                                        }
                                    }
                                });

                                // Initialize on page load
                                const currentValue = hiddenInterval.value;
                                toggleDaysField(currentValue);

                                if (currentValue && parseInt(currentValue) === 1) {
                                    const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
                                    dayCheckboxes.forEach(checkbox => {
                                        checkbox.checked = true;
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Task List Context -->
                    <x-sidebar-box type="neutral" title="Adding to {{ $taskList->name }}" icon="list">
                        <div class="space-y-2 text-sm">
                            <p><strong>Group:</strong> {{ $group->name }}</p>
                            <p><strong>Task List:</strong> {{ $taskList->name }}</p>
                            @if($taskList->tasks->count() > 0)
                                <div class="border-t border-gray-200 dark:border-gray-600 mt-3 pt-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Existing tasks:</p>
                                    @foreach($taskList->tasks->take(3) as $task)
                                        <div class="text-xs text-gray-700 dark:text-gray-300">{{ $task->name }}</div>
                                    @endforeach
                                    @if($taskList->tasks->count() > 3)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            and {{ $taskList->tasks->count() - 3 }} more...
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </x-sidebar-box>

                    <!-- Task Tips -->
                    <x-sidebar-box type="info" title="Task Tips" icon="lightbulb">
                        <ul class="text-sm space-y-1">
                            <li>• Use clear, actionable titles</li>
                            <li>• Set realistic intervals and days</li>
                            <li>• Mark optional tasks appropriately</li>
                            <li>• Set start/end dates for time-bound tasks</li>
                        </ul>
                    </x-sidebar-box>
                </div>
            </div>
    </div>
</x-app-layout>
