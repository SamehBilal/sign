<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Start Process') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Upload CSV File') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Upload a CSV file to start the flow.') }}
                            </p>
                        </header>

                        <form method="POST" id="csvUploadForm" class="mt-6 space-y-6" action="{{ route('upload.csv') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="csvFile" :value="__('Select CSV File:')" />
                                <input id="csvFile" name="file" type="file"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-gray-300 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                                    accept=".csv" required />
                                <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            </div>

                            <div class="flex items-center gap-4 status-container"></div>
                            {{-- <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Upload') }}</x-primary-button>
                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600">{{ __('Uploaded.') }}</p>
                                @endif
                            </div> --}}
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section id="upload-section">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Extracted Data') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Below you will find the extracted data from the uploaded file.') }}
                            </p>
                        </header>

                        <div id="loading" class="mt-3 text-sm text-gray-500 hidden">
                            <p>{{ __('Loading... Please wait.') }}</p>
                        </div>

                        <div id="data-section" class="mt-6 hidden">
                            <form id="bulkSendForm" method="POST" action="{{ route('send.bulk.agreements') }}">
                                @csrf

                                <!-- Template Selection Dropdown -->
                                <div class="mb-4">
                                    <x-input-label for="template" :value="__('Select Template')" />
                                    <select id="template" name="template_id"
                                        class="mt-1 block w-full text-sm text-gray-500" required>
                                        @if (array_key_exists('libraryDocumentList', $templates))
                                            @foreach ($templates['libraryDocumentList'] as $template)
                                                <option value="{{ $template['id'] }}"
                                                    data-name="{{ $template['name'] }}">
                                                    {{ $template['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('template_id')" />
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    #
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Select') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Name') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Email') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Bank Account') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Data 1') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Data 2') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    <x-primary-button
                                        type="submit">{{ __('Send Selected Agreements') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </section>
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
                        const loadingElement = document.getElementById('loading');
                        const dataSection = document.getElementById('data-section');
                        const dataTableBody = document.getElementById('dataTableBody');
                        const statusContainer = document.querySelector('.status-container');

                        loadingElement.classList.remove('hidden');
                        dataSection.classList.add('hidden');
                        dataTableBody.innerHTML = '';

                        fetch("{{ route('upload.csv') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                loadingElement.classList.add('hidden');
                                if (data.results) {
                                    const extractedData = data.results;
                                    extractedData.forEach((item, index) => {
                                        const row = `<tr>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${index + 1}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                                            <input type="checkbox" name="selected[]" value="${index}" data-name="${item.name}" data-email="${item.email}" data-bank-account="${item.bank_account}" data-data1="${item.data1}" data-data2="${item.data2}">
                                                        </td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.name}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.email}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.bank_account}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.data1}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.data2}</td>
                                                    </tr>`;
                                        dataTableBody.insertAdjacentHTML('beforeend', row);
                                    });
                                    dataSection.classList.remove('hidden');
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

                    document.getElementById('bulkSendForm').addEventListener('submit', function(e) {
                        const selectedRows = document.querySelectorAll('#dataTableBody input[name="selected[]"]:checked');
                        if (selectedRows.length === 0) {
                            e.preventDefault();
                            alert('Please select at least one user.');
                            return;
                        }

                        const agreements = [];
                        selectedRows.forEach((row) => {
                            agreements.push({
                                name: row.dataset.name,
                                email: row.dataset.email,
                                bank_account: row.dataset.bankAccount,
                                data1: row.dataset.data1,
                                data2: row.dataset.data2,
                            });
                        });

                        const agreementsInput = document.createElement('input');
                        agreementsInput.type = 'hidden';
                        agreementsInput.name = 'agreements';
                        agreementsInput.value = JSON.stringify(agreements);

                        const templateIdInput = document.createElement('input');
                        templateIdInput.type = 'hidden';
                        templateIdInput.name = 'template_id';
                        templateIdInput.value = document.getElementById('template').value;

                        const templateName = document.querySelector('#template option:checked').text;

                        const templateNameInput = document.createElement('input');
                        templateNameInput.type = 'hidden';
                        templateNameInput.name = 'template_name';
                        templateNameInput.value = templateName;

                        this.appendChild(templateIdInput);
                        this.appendChild(templateNameInput);
                        this.appendChild(agreementsInput);
                    });
                </script>
            </div>
        </div>
    </div>
    <br>
</x-app-layout>
