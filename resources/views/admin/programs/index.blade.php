<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Training Programs (Admin)') }}
            </h2>
            <a href="{{ route('admin.programs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition-all">
                + Create Program
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Programs</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPrograms }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Trainees</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalTrainees }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">System Status</div>
                    <div class="mt-1 text-3xl font-semibold text-amber-600">Active</div>
                </div>
            </div>

            <!-- Analytics Dashboards (Chart.js) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Chart 1: Trainer Leaderboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 heading-font">Trainer Leaderboard</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="trainerChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Program Satisfaction -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 heading-font">Program Satisfaction</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="programChart"></canvas>
                    </div>
                </div>

                <!-- Chart 4: Trainee Engagement -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 heading-font">Trainee Engagement</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="engagementChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Programs Table with Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    
                    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                        <div class="flex-1 max-w-md relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                            </div>
                            <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Filter by Title, Area, or Trainer...">
                        </div>
                        <div>
                            <button id="exportCsvBtn" class="inline-flex items-center px-4 py-2 border border-blue-500 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Export to CSV
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="programsTable" class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Training Area</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trainer</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($programs as $program)
                                    <tr class="program-row">
                                        <td class="px-6 py-4 whitespace-nowrap title-cell font-medium text-gray-900">{{ $program->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap area-cell text-gray-600">{{ $program->training_area }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap venue-cell text-gray-600">{{ $program->venue }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $program->schedule_datetime->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap trainer-cell text-gray-600">{{ $program->trainer->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr id="emptyRow">
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No training programs created yet.</td>
                                    </tr>
                                @endforelse
                                <tr id="noResultsRow" class="hidden">
                                     <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center text-gray-500">No programs match your search filter.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // 1. Trainer Performance (Bar)
            const trainerData = @json($trainerPerformance);
            if(trainerData && trainerData.length > 0) {
                new Chart(document.getElementById('trainerChart'), {
                    type: 'bar',
                    data: {
                        labels: trainerData.map(t => t.name),
                        datasets: [{
                            label: 'Average Rating (out of 5)',
                            data: trainerData.map(t => t.rating),
                            backgroundColor: '#3b82f6',
                            borderRadius: 4
                        }]
                    },
                    options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 5 } } }
                });
            }

            // 3. Program Satisfaction (Line)
            const programData = @json($programSatisfaction);
            if(programData && programData.length > 0) {
                new Chart(document.getElementById('programChart'), {
                    type: 'line',
                    data: {
                        labels: programData.map(p => (p.title.length > 15 ? p.title.substring(0,15) + '...' : p.title)),
                        datasets: [{
                            label: 'Satisfaction Score',
                            data: programData.map(p => p.rating),
                            borderColor: '#10b981',
                            tension: 0.3,
                            fill: true,
                            backgroundColor: 'rgba(16, 185, 129, 0.1)'
                        }]
                    },
                    options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 5 } } }
                });
            }

            // 4. Trainee Engagement (Doughnut)
            const engagementData = @json($engagementData);
            if(engagementData && (engagementData.submitted > 0 || engagementData.no_feedback > 0)) {
                new Chart(document.getElementById('engagementChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Submitted Feedback', 'No Feedback (Attended)'],
                        datasets: [{
                            data: [engagementData.submitted, engagementData.no_feedback],
                            backgroundColor: ['#f59e0b', '#e5e7eb'],
                            borderWidth: 0
                        }]
                    },
                    options: { maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom' } } }
                });
            }

            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.program-row');
            const noResultsRow = document.getElementById('noResultsRow');

            // Quick live filter
            if(searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    const term = e.target.value.toLowerCase();
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const title = row.querySelector('.title-cell').textContent.toLowerCase();
                        const area = row.querySelector('.area-cell').textContent.toLowerCase();
                        const trainer = row.querySelector('.trainer-cell').textContent.toLowerCase();
                        
                        if (title.includes(term) || area.includes(term) || trainer.includes(term)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if(visibleCount === 0 && rows.length > 0) {
                        noResultsRow.classList.remove('hidden');
                    } else {
                        noResultsRow.classList.add('hidden');
                    }
                });
            }

            // Export to CSV Script
            document.getElementById('exportCsvBtn')?.addEventListener('click', function() {
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "Title,Training Area,Venue,Schedule,Trainer\n";
                
                rows.forEach(row => {
                    // Only export visible rows
                    if(row.style.display !== 'none') {
                        let rowData = [];
                        row.querySelectorAll('td').forEach(col => {
                            // Escape commas and quotes for CSV integrity
                            let text = col.innerText.replace(/"/g, '""');
                            rowData.push(`"${text}"`);
                        });
                        csvContent += rowData.join(",") + "\n";
                    }
                });

                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "training_programs.csv");
                document.body.appendChild(link); 
                link.click();
                document.body.removeChild(link);
            });
        });
    </script>
</x-app-layout>
