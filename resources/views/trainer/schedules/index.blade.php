<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Training Schedules (Trainer)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'upcoming' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Upcoming Classes</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $upcomingCount }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Average Rating</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($avgRating, 1) }} ★</div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
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
                        Completed Classes
                    </button>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="mt-6">
                
                <!-- UPCOMING TAB -->
                <div x-show="tab === 'upcoming'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    @php $hasUpcoming = false; @endphp
                    @foreach ($programs as $program)
                        @if($program->schedule_datetime >= now())
                            @php $hasUpcoming = true; @endphp
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-400">
                                <div class="border-b pb-4 mb-4">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $program->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span class="font-semibold text-gray-700">Area:</span> {{ $program->training_area }} | 
                                        <span class="font-semibold text-gray-700">Venue:</span> {{ $program->venue }} | 
                                        <span class="font-semibold text-gray-700">Schedule:</span> {{ $program->schedule_datetime->format('M d, Y H:i A') }}
                                    </p>
                                </div>

                                <div class="w-full">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center justify-between">
                                        Enrolled Trainees
                                        <span class="text-sm font-normal text-gray-500">
                                            {{ $program->trainees->where('pivot.is_present', true)->count() }} / {{ $program->trainees->count() }} Present
                                        </span>
                                    </h4>
                                    @if($program->trainees->count() > 0)
                                        <ul class="space-y-3">
                                            @foreach($program->trainees as $trainee)
                                                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border {{ $trainee->pivot->is_present ? 'border-green-200' : 'border-gray-200' }}">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $trainee->name }}</div> 
                                                        <div class="text-xs text-gray-500">{{ $trainee->email }}</div>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        @if($trainee->pivot->is_present)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Present</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Pending</span>
                                                        @endif
                                                        
                                                        <form method="POST" action="{{ route('trainer.schedules.attendance', ['program' => $program->id, 'trainee' => $trainee->id]) }}">
                                                            @csrf
                                                            @php $isFuture = $program->schedule_datetime > now(); @endphp
                                                            <input type="hidden" name="is_present" value="{{ $trainee->pivot->is_present ? '0' : '1' }}">
                                                            <button type="submit" {{ $isFuture ? 'disabled' : '' }} title="{{ $isFuture ? 'Attendance locked until program starts' : '' }}" class="text-xs px-3 py-1.5 focus:outline-none bg-white border {{ $isFuture ? 'border-gray-300 text-gray-400 bg-gray-50 cursor-not-allowed' : ($trainee->pivot->is_present ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-indigo-300 text-indigo-700 hover:bg-indigo-50') }} rounded shadow-sm transition">
                                                                {{ $trainee->pivot->is_present ? 'Mark Absent' : 'Mark Present' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500 italic bg-gray-50 p-4 rounded-lg">No trainees enrolled currently.</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
                    @if(!$hasUpcoming)
                        <div class="text-center py-10 bg-gray-50 rounded-lg text-gray-500">
                            No upcoming programs scheduled.
                        </div>
                    @endif
                </div>

                <!-- COMPLETED TAB -->
                <div x-show="tab === 'completed'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                    @php $hasCompleted = false; @endphp
                    @foreach ($programs as $program)
                        @if($program->schedule_datetime < now())
                            @php $hasCompleted = true; @endphp
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-gray-400 opacity-90">
                                <div class="border-b pb-4 mb-4 flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $program->title }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Completed on {{ $program->schedule_datetime->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-100 px-3 py-1 rounded text-sm text-gray-600 font-semibold">
                                        {{ $program->trainees->where('pivot.is_present', true)->count() }} Attendees
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Roster -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center justify-between">
                                            Final Class Roster
                                        </h4>
                                        @if($program->trainees->count() > 0)
                                            <ul class="space-y-3">
                                                @foreach($program->trainees as $trainee)
                                                    <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border {{ $trainee->pivot->is_present ? 'border-green-200' : 'border-red-200' }}">
                                                        <div>
                                                            <div class="font-medium text-gray-900">{{ $trainee->name }}</div> 
                                                            <div class="text-xs text-gray-500">{{ $trainee->email }}</div>
                                                        </div>
                                                        @if($trainee->pivot->is_present)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Present</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Absent</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-500 italic bg-gray-50 p-4 rounded-lg">No trainees were enrolled.</p>
                                        @endif
                                    </div>

                                    <!-- Feedback -->
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Trainee Feedback</h4>
                                        @if($program->feedbacks && $program->feedbacks->count() > 0)
                                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                                @foreach($program->feedbacks as $feedback)
                                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 shadow-sm relative">
                                                        <p class="font-semibold text-sm">{{ $feedback->user->name ?? 'Unknown' }} - <span class="text-yellow-500">{{ str_repeat('★', $feedback->rating) }}{{ str_repeat('☆', 5 - $feedback->rating) }}</span></p>
                                                        @if($feedback->comments)
                                                            <p class="text-gray-700 text-sm mt-2 italic">"{{ $feedback->comments }}"</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-500 italic bg-gray-50 p-4 rounded-lg">No feedback submitted yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
                    @if(!$hasCompleted)
                        <div class="text-center py-10 bg-gray-50 rounded-lg text-gray-500">
                            You have no completed classes yet.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
