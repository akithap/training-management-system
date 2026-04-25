<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Curriculum') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'upcoming', activeModal: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Upcoming Programs
                    </button>
                    <button @click="tab = 'completed'" :class="tab === 'completed' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Completed & Certificates
                    </button>
                </nav>
            </div>

            <!-- Content Container -->
            <div class="mt-6">
                <!-- Upcoming Tab -->
                <div x-show="tab === 'upcoming'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    @php $hasUpcoming = false; @endphp
                    @foreach ($programs as $program)
                        @if(!$program->is_completed)
                            @php $hasUpcoming = true; @endphp
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                                <div class="flex flex-col md:flex-row justify-between md:items-center">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $program->title }}</h3>
                                        <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
                                            <div class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>{{ $program->trainer->name ?? 'TBD' }}</div>
                                            <div class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>{{ $program->venue }}</div>
                                            <div class="flex items-center font-medium text-indigo-600"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>{{ $program->schedule_datetime->format('M d, Y H:i A') }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-4 md:mt-0">
                                        @if($program->file_path)
                                            @php $filename = basename($program->file_path); @endphp
                                            <a href="{{ route('downloads.programs', ['filename' => $filename]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-100 transition shadow-sm">
                                                Instructor Materials
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if(!$hasUpcoming)
                        <div class="text-center py-10 bg-gray-50 rounded-lg text-gray-500">
                            No upcoming programs scheduled for you. Enjoy the break!
                        </div>
                    @endif
                </div>

                <!-- Completed Tab -->
                <div x-show="tab === 'completed'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    @php $hasCompleted = false; @endphp
                    @foreach ($programs as $program)
                        @if($program->is_completed)
                            @php 
                                $hasCompleted = true; 
                                $userFeedback = $program->feedbacks->first();
                                $isPresent = $program->pivot->is_present ?? false;
                            @endphp
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-400 opacity-90">
                                <div class="flex flex-col md:flex-row justify-between md:items-center">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">{{ $program->title }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">Completed on {{ $program->schedule_datetime->format('M d, Y') }}</p>
                                        
                                        @if($isPresent)
                                            <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mx-1">Verified Attendance</span>
                                            
                                            <!-- Certificate Button -->
                                            @if($userFeedback)
                                                <a href="{{ route('trainee.programs.certificate', $program->id) }}" target="_blank" class="mt-3 inline-flex items-center text-sm font-medium text-amber-600 hover:text-amber-800">
                                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                                    View Certificate
                                                </a>
                                            @endif
                                        @else
                                            <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded mx-1">Marked Absent</span>
                                        @endif
                                    </div>
                                    <div class="mt-4 md:mt-0 text-right">
                                        @if($isPresent)
                                            @if($userFeedback)
                                                <div class="text-yellow-500 text-lg">{{ str_repeat('★', $userFeedback->rating) }}{{ str_repeat('☆', 5 - $userFeedback->rating) }}</div>
                                                <span class="text-xs text-gray-500 block">Feedback Submitted</span>
                                            @else
                                                <button @click="activeModal = {{ $program->id }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md shadow hover:bg-indigo-700 transition">
                                                    Rate Program
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400 italic">Attendance required for feedback</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Feedback Modal via Alpine JS -->
                            <div x-show="activeModal === {{ $program->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="activeModal === {{ $program->id }}" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="activeModal = null"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="activeModal === {{ $program->id }}" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Rate: {{ $program->title }}</h3>
                                            <div class="mt-2 text-sm text-gray-500">Your feedback helps us continuously improve our systems.</div>
                                            <form action="{{ route('trainee.programs.feedback', $program->id) }}" method="POST" class="mt-4" id="form-{{$program->id}}">
                                                @csrf
                                                <div class="mb-4">
                                                    <x-input-label for="rating_{{ $program->id }}" :value="__('Overall Rating')" />
                                                    <select id="rating_{{ $program->id }}" name="rating" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                                        <option value="" disabled selected>Select rating...</option>
                                                        <option value="5">5 - Excellent (Exceeded Expectations)</option>
                                                        <option value="4">4 - Good (Met Expectations)</option>
                                                        <option value="3">3 - Average</option>
                                                        <option value="2">2 - Poor</option>
                                                        <option value="1">1 - Terrible (Needs Improvement)</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <x-input-label for="comments_{{ $program->id }}" :value="__('Detailed Comments (Optional)')" />
                                                    <textarea id="comments_{{ $program->id }}" name="comments" rows="4" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" form="form-{{$program->id}}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                                Submit Feedback
                                            </button>
                                            <button @click="activeModal = null" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if(!$hasCompleted)
                        <div class="text-center py-10 bg-gray-50 rounded-lg text-gray-500">
                            You haven't completed any programs yet.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
