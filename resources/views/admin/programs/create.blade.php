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

                        <!-- Schedule -->
                        <div class="mb-4">
                            <x-input-label for="schedule_datetime" :value="__('Schedule Date & Time')" />
                            <x-text-input id="schedule_datetime" class="block mt-1 w-full" type="datetime-local" name="schedule_datetime" :value="old('schedule_datetime')" required />
                            <x-input-error :messages="$errors->get('schedule_datetime')" class="mt-2" />
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

                        <!-- Trainees Enrollment -->
                        <div class="mb-4">
                            <x-input-label for="trainees" :value="__('Enroll Trainees')" />
                            <select id="trainees" name="trainees[]" multiple class="block mt-1 w-full h-32 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($trainees as $trainee)
                                    <option value="{{ $trainee->id }}" {{ (is_array(old('trainees')) && in_array($trainee->id, old('trainees'))) ? 'selected' : '' }}>{{ $trainee->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold CTRL (or CMD) to select multiple.</p>
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
</x-app-layout>
