<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Training Program') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Flatpickr CSS -->
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Training Area -->
                        <div class="mb-4">
                            <x-input-label for="training_area" :value="__('Training Area')" />
                            <x-text-input id="training_area" class="block mt-1 w-full" type="text" name="training_area" :value="old('training_area')" required />
                            <x-input-error :messages="$errors->get('training_area')" class="mt-2" />
                        </div>

                        <!-- Venue -->
                        <div class="mb-4">
                            <x-input-label for="venue" :value="__('Venue')" />
                            <x-text-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue')" required />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <!-- Schedule Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="schedule_datetime" :value="__('Start Date & Time')" />
                                <input id="schedule_datetime" class="flatpickr block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="schedule_datetime" value="{{ old('schedule_datetime') }}" placeholder="Select Start Date/Time..." required />
                                <x-input-error :messages="$errors->get('schedule_datetime')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="end_datetime" :value="__('End Date & Time (Optional)')" />
                                <input id="end_datetime" class="flatpickr block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="end_datetime" value="{{ old('end_datetime') }}" placeholder="Select End Date/Time..." />
                                <x-input-error :messages="$errors->get('end_datetime')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Trainer -->
                        <div class="mb-4">
                            <x-input-label for="trainer_id" :value="__('Assign Trainer')" />
                            <select id="trainer_id" name="trainer_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Select a Trainer...</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('trainer_id')" class="mt-2" />
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <x-input-label for="file_upload" :value="__('Training Materials (PDF, DOC, ZIP up to 5MB)')" />
                            <input id="file_upload" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-1 border" type="file" name="file_upload" />
                            <x-input-error :messages="$errors->get('file_upload')" class="mt-2" />
                        </div>

                        <!-- Trainees Enrollment Checkboxes -->
                        <div class="mb-4">
                            <x-input-label :value="__('Enroll Trainees')" class="mb-2" />
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-4 border border-gray-300 rounded-md bg-gray-50 shadow-inner">
                                @forelse($trainees as $trainee)
                                    <label class="flex items-start gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-indigo-50 cursor-pointer transition">
                                        <div class="flex items-center h-5 mt-1">
                                            <input type="checkbox" name="trainees[]" value="{{ $trainee->id }}" class="w-5 h-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ (is_array(old('trainees')) && in_array($trainee->id, old('trainees'))) ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-sm font-bold text-gray-900">{{ $trainee->name }}</span>
                                            <span class="block text-xs text-gray-500">{{ $trainee->email }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-full text-center text-sm text-gray-500 py-2">No active trainees available in the system.</div>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('trainees')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Create Program') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".flatpickr", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "F j, Y h:i K"
            });
        });
    </script>
</x-app-layout>
