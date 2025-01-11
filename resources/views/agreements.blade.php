<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agreements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Upload Document') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Upload a transient document to get the id then to create an agreement from it.') }}
                            </p>
                        </header>

                        <form method="POST" id="csvUploadForm" class="mt-6 space-y-6"
                            action="{{ route('upload.agreement') }}" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="csvFile" :value="__('Select Document:')" />
                                <input id="csvFile" name="file" type="file"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-gray-300 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                                    accept=".pdf" required />
                                <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-4 status-container"></div>
                                {{-- <x-primary-button>{{ __('Upload') }}</x-primary-button> --}}
                                @if (session('status') === 'template-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden" id="make-template">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Make An Agreement') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('create an agreement from a transient document.') }}
                            </p>
                        </header>

                        <form id="make-template" class="mt-6 space-y-6" method="post"
                            action="{{ route('agreements.store') }}">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('File Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name')" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <x-input-label for="email" :value="__('Receptient Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                :value="old('email')" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            <div>
                                <x-input-label for="id" :value="__('Transient ID')" />
                                <x-text-input id="id" name="id" type="text" class="mt-1 block w-full"
                                    :value="old('id')" />
                                <x-input-error class="mt-2" :messages="$errors->get('id')" />
                            </div>

                            {{-- <div class="flex items-center gap-4 status-container"></div> --}}
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                @if (session('status') === 'template-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>

    <script>
        document.getElementById('csvFile').addEventListener('change', function() {
            const form = document.getElementById('csvUploadForm');
            form.dispatchEvent(new Event('submit', {
                bubbles: true,
                cancelable: true
            }));
        });
        document.getElementById('csvUploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const loadingElement = document.getElementById('make-template');
            const dataId = document.getElementById('id');
            const dataName = document.getElementById('name');
            const statusContainer = document.querySelector('.status-container');
            loadingElement.classList.remove('hidden');

            fetch("{{ route('upload.agreement') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.results) {
                        dataId.value = data.results.id;
                        dataName.value = data.results.name;
                        statusContainer.innerHTML = `<p class="text-sm text-gray-600 fade-out">Uploaded.</p>`;
                        setTimeout(() => {
                            statusContainer.innerHTML = '';
                        }, 1000);
                    } else {
                        alert('Failed to extract data: ' + data.results);
                    }
                })
                .catch(error => {
                    loadingElement.classList.add('hidden');
                    alert('An error occurred. Please try again.');
                    console.error(error);
                });
        });
    </script>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Agreements List') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Below you\'ll find a list of all the agreements.') }}
                            </p>
                        </header>

                        <div class="mt-6 overflow-x-auto">
                            <table id="agreementsTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Name') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('ID') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Signer') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="dataTableBody" class="bg-white divide-y divide-gray-200">
                                    @if (array_key_exists('userAgreementList', $agreements))
                                        @foreach ($agreements['userAgreementList'] as $index => $agreement)
                                            <tr>
                                                <td class="px-6 text-sm py-4 whitespace-nowrap">{{ $index + 1 }}
                                                </td>
                                                <td class="px-6 text-sm py-4 whitespace-nowrap">
                                                    {{ $agreement['name'] }}</td>
                                                <td class="px-6 text-sm py-4 whitespace-nowrap">
                                                    {{ $agreement['id'] }}</td>
                                                <td class="px-6 text-sm py-4 whitespace-nowrap">
                                                    {{ $agreement['displayDate'] }}</td>
                                                <td class="px-6 text-sm py-4 whitespace-nowrap">
                                                    {{ $agreement['displayParticipantSetInfos'][0]['displayUserSetMemberInfos'][0]['fullName'] }}
                                                    ({{ $agreement['displayParticipantSetInfos'][0]['displayUserSetMemberInfos'][0]['email'] }})
                                                </td>
                                                <td class="px-6 text-sm py-4 whitespace-nowrap">
                                                    {{ $agreement['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <br>

    <!-- DataTables CSS & JS -->
    @push('styles')
        <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
        {{-- <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet"> --}}

        <style>
            .dataTables_length select {
                margin-left: 0.5rem;
                width: 3.5em;
            }

            .dt-buttons {
                margin-right: 1rem;
                margin-bottom: 1rem;
            }

            .top {
                margin-bottom: 4rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

        <script>
            $(document).ready(function() {
                var table = $('#agreementsTable').DataTable({
                    dom: '<"top"Bfl>rt<"bottom"ip>',
                    buttons: [{
                            extend: 'copy',
                            className: 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                        },
                        {
                            extend: 'excel',
                            className: 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                        },
                        {
                            extend: 'csv',
                            className: 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                        },
                        {
                            extend: 'pdf',
                            className: 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                        }
                    ],
                    responsive: true,
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: [3], // Index of the Published At column
                        render: function(data) {
                            if (data) {
                                const localDate = new Date(data).toLocaleDateString(undefined, {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                return localDate;
                            }
                            return 'N/A'; // If data is null or undefined
                        }
                    }],
                    lengthMenu: [5, 10, 25, 50, 100], // Customize "Per Page" options
                    pageLength: 10, // Default number of entries per page
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ ",
                        zeroRecords: "No matching records found",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No agreements available",
                        infoFiltered: "(filtered from _MAX_ total records)"
                    },
                    initComplete: function() {
                        // Apply custom classes to the per-page select dropdown and pagination controls
                        $('.dataTables_length select').addClass(
                            'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'
                            );
                        $('.dataTables_length label').addClass('block font-medium text-sm text-gray-700');
                        $('.dataTables_filter label').addClass('block font-medium text-sm text-gray-700');
                        $('.dataTables_filter input').addClass(
                            'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'
                            );
                        $('.dataTables_paginate').addClass('flex space-x-2 mt-4');
                        $('.dataTables_paginate a').addClass(
                            'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                            );
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
