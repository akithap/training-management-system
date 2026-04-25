<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Global Feedback & Reviews Database') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @forelse($programs as $program)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $program->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Concluded on {{ $program->schedule_datetime->format('M d, Y') }} | Trainer: <span class="font-semibold text-gray-700">{{ $program->trainer->name ?? 'Unassigned' }}</span></p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                {{ $program->feedbacks->count() }} Reviews Logged
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                        <!-- Trainer Review -->
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Trainer's Concluding Remarks</h4>
                            @if($program->trainer_review)
                                <div class="p-4 bg-indigo-50 border-l-4 border-indigo-500 rounded text-sm text-indigo-900 whitespace-pre-wrap italic">"{{ $program->trainer_review }}"</div>
                            @else
                                <p class="text-sm text-gray-500 bg-gray-50 p-4 rounded text-center">No official remarks left by the trainer.</p>
                            @endif
                        </div>

                        <!-- Trainee Reviews -->
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Trainee Course Feedback</h4>
                            @if($program->feedbacks->count() > 0)
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    @foreach($program->feedbacks as $feedback)
                                        <div class="p-4 bg-white border border-gray-200 shadow-sm rounded-lg relative hover:shadow-md transition">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="font-semibold text-sm text-gray-900">{{ $feedback->user->name ?? 'Anonymous' }}</div>
                                                <div class="text-yellow-500 text-sm">
                                                    {{ str_repeat('★', $feedback->rating) }}<span class="text-gray-300">{{ str_repeat('★', 5 - $feedback->rating) }}</span>
                                                </div>
                                            </div>
                                            @if($feedback->comments)
                                                <p class="text-sm text-gray-600 mt-1">"{{ $feedback->comments }}"</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 bg-gray-50 p-4 rounded text-center">No trainee feedback has been submitted yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 text-center shadow-sm sm:rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Review Data</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no permanently completed programs in the system database yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
