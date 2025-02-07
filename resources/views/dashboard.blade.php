<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Start Process') }}
        </h2>
    </x-slot>

    @if (session('status') == 'Agreements sent successfully!')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "{{ __('Agreements sent successfully!') }}",
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "center",
                    backgroundColor: "rgb(31 41 55 / 1)",
                    stopOnFocus: true,
                    className: "rounded-toast",
                }).showToast();
            });
        </script>
    @endif

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

                                <div class="mb-4">
                                    <x-input-label for="template" :value="__('Select Template')" />
                                    <select id="template" name="template_id"
                                        class="mt-1 block w-full text-sm text-gray-500" required
                                        onchange="loadDocumentTemplate()">
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
                                                    {{ __('Vendor') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Project') }}
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
                                                    {{ __('Bank Name') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('IBAN') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Bank Address') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Currency') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Swift Code') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Address') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Mobile') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Amount') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Passport') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Invoice') }}
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Date') }}
                                                </th>

                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Image') }}
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
                                                            <input type="checkbox" name="selected[]" value="${index}" data-vendor="${item.vendor}" data-full_name="${item.full_name}" data-email="${item.email}" data-bank_name="${item.bank_name}" data-iban="${item.iban}" data-bank_address="${item.bank_address}" data-currency="${item.currency}" data-swift_code="${item.swift_code}" data-address="${item.address}" data-mobile="${item.mobile}" data-project="${item.project}" data-project1="${item.project1}" data-project2="${item.project2}" data-project3="${item.project3}" data-project4="${item.project4}" data-project5="${item.project5}" data-project6="${item.project6}" data-project7="${item.project7}" data-amount="${item.amount}" data-amount1="${item.amount1}" data-amount2="${item.amount2}" data-amount3="${item.amount3}" data-amount4="${item.amount4}" data-amount5="${item.amount5}" data-amount6="${item.amount6}" data-amount7="${item.amount7}" data-passport="${item.passport}" data-invoice="${item.invoice}" data-date="${item.date}" data-image="${item.image}">
                                                        </td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.vendor}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.project}, ${item.project1}, ${item.project2}, ${item.project3}, ${item.project4}, ${item.project5}, ${item.project6}, ${item.project7}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.full_name}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.email}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.bank_name}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.iban}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.bank_address}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.currency}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.swift_code}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.address}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.mobile}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.amount}, ${item.amount1}, ${item.amount2}, ${item.amount3}, ${item.amount4}, ${item.amount5}, ${item.amount6}, ${item.amount7}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.passport}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.invoice}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap">${item.date}</td>
                                                        <td class="px-6 py-4 text-sm whitespace-nowrap"><img src="${item.image}"></td>
                                                    </tr>`;
                                        dataTableBody.insertAdjacentHTML('beforeend', row);
                                    });
                                    dataSection.classList.remove('hidden');
                                    statusContainer.innerHTML = `<p class="text-sm text-gray-600 fade-out">Uploaded.</p>`;
                                    Toastify({
                                        text: "{{ __('File uploaded successfully') }}",
                                        duration: 3000,
                                        close: true,
                                        gravity: "bottom",
                                        position: "center",
                                        backgroundColor: "rgb(31 41 55 / 1)",
                                        stopOnFocus: true,
                                        className: "rounded-toast",
                                    }).showToast();
                                    setTimeout(() => {
                                        statusContainer.innerHTML = '';
                                    }, 1000);
                                } else {
                                    Toastify({
                                        text: "{{ __('Failed to extract data') }}",
                                        duration: 3000,
                                        close: true,
                                        gravity: "bottom",
                                        position: "center",
                                        backgroundColor: "rgb(31 41 55 / 1)",
                                        stopOnFocus: true,
                                        className: "rounded-toast",
                                    }).showToast();
                                }
                            })
                            .catch(error => {
                                loadingElement.classList.add('hidden');
                                Toastify({
                                    text: "{{ __('An error occurred. Please try again.') }}",
                                    duration: 3000,
                                    close: true,
                                    gravity: "bottom",
                                    position: "center",
                                    backgroundColor: "rgb(31 41 55 / 1)",
                                    stopOnFocus: true,
                                    className: "rounded-toast",
                                }).showToast();
                            });
                    });

                    document.getElementById('bulkSendForm').addEventListener('submit', function(e) {
                        const selectedRows = document.querySelectorAll('#dataTableBody input[name="selected[]"]:checked');
                        if (selectedRows.length === 0) {
                            e.preventDefault();
                            Toastify({
                                text: "{{ __('Please select at least one user.') }}",
                                duration: 3000,
                                close: true,
                                gravity: "bottom",
                                position: "center",
                                backgroundColor: "rgb(31 41 55 / 1)",
                                stopOnFocus: true,
                                className: "rounded-toast",
                            }).showToast();
                            return;
                        }

                        const agreements = [];
                        selectedRows.forEach((row) => {
                            agreements.push({
                                vendor: row.dataset.vendor,
                                full_name: row.dataset.full_name,
                                email: row.dataset.email,
                                bank_name: row.dataset.bank_name,
                                iban: row.dataset.iban,
                                bank_address: row.dataset.bank_address,
                                currency: row.dataset.currency,
                                swift_code: row.dataset.swift_code,
                                address: row.dataset.address,
                                mobile: row.dataset.mobile,
                                project: row.dataset.project,
                                project1: row.dataset.project1,
                                project2: row.dataset.project2,
                                project3: row.dataset.project3,
                                project4: row.dataset.project4,
                                project5: row.dataset.project5,
                                project6: row.dataset.project6,
                                project7: row.dataset.project7,
                                amount: row.dataset.amount,
                                amount1: row.dataset.amount1,
                                amount2: row.dataset.amount2,
                                amount3: row.dataset.amount3,
                                amount4: row.dataset.amount4,
                                amount5: row.dataset.amount5,
                                amount6: row.dataset.amount6,
                                amount7: row.dataset.amount7,
                                passport: row.dataset.passport,
                                invoice: row.dataset.invoice,
                                date: row.dataset.date,
                                image: row.dataset.image,
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

                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

                <script>
                    function loadDocumentTemplate() {
                        let loadingToast = Toastify({
                            text: `
                                <div class="flex items-center space-x-2">
                                    <!-- Using Tailwind classes for icon -->
                                    <i class="fas fa-sync-alt fa-spin text-white"></i>
                                    <span>{{ __('Previewing template....') }}</span>
                                </div>`,
                            duration: -1, // indefinite duration
                            close: false, // Hide close button
                            gravity: "bottom",
                            position: "center",
                            backgroundColor: "rgb(31 41 55 / 1)", // Dark background color
                            stopOnFocus: true,
                            className: "rounded-toast",
                            escapeMarkup: false, // Allow HTML rendering
                        }).showToast();


                        // Add rotating icon to the toast
                        /* let iconElement = document.createElement("i");
                        iconElement.classList.add("fas", "fa-sync-alt", "fa-spin"); // Add spinning reload icon
                        loadingToast.node.prepend(iconElement); // Prepend the icon to the toast */

                        const selectElement = document.getElementById('template');
                        const selectedOption = selectElement.options[selectElement.selectedIndex];
                        const templateId = selectedOption.value;

                        window.openDocumentModal(templateId, event, function() {
                            loadingToast.hideToast();
                            Toastify({
                                text: "{{ __('Preview successful!') }}",
                                duration: 3000,
                                close: true,
                                gravity: "bottom",
                                position: "center",
                                backgroundColor: "rgb(31 41 55 / 1)",
                                stopOnFocus: true,
                                className: "rounded-toast"
                            }).showToast();
                        });
                    }

                    window.openDocumentModal = function(id, event, callback) {
                        const url = `{{ route('templates.file', ['id' => '__id__']) }}`.replace('__id__', id);
                        const dataModalBody = document.getElementById('document-content');
                        const button = event.currentTarget;
                        dataModalBody.innerHTML = ''; // Clear previous content
                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.arrayBuffer())
                            .then(data => {
                                const loadingTask = pdfjsLib.getDocument(data);
                                loadingTask.promise.then(pdf => {
                                    console.log('PDF loaded');
                                    // Get the first page of the PDF
                                    pdf.getPage(1).then(page => {
                                        console.log('Page loaded');

                                        const canvas = document.getElementById('pdfCanvas');
                                        const context = canvas.getContext('2d');

                                        const viewport = page.getViewport({
                                            scale: 1
                                        });
                                        canvas.width = viewport.width;
                                        canvas.height = viewport.height;

                                        // Render the page
                                        const renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        page.render(renderContext);
                                    });
                                });
                                const modalName = 'document-modal';
                                window.dispatchEvent(new CustomEvent('open-modal', {
                                    detail: modalName
                                }));
                            })
                            .catch(error => {
                                console.error('Error fetching document:', error);
                                const detailsDiv = document.getElementById('document-content');
                                detailsDiv.innerHTML = `<p>Error loading document.</p>`;
                            })
                            .finally(() => {
                                if (callback) callback();
                            });
                    };
                </script>

                <x-modal :name="'document-modal'" :show="false" focusable lg>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Template Document') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Below you will find the PDF document related to this template.') }}
                        </p>

                        <canvas class="mt-6" id="pdfCanvas"></canvas>
                        <div id="document-content"></div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button class="ms-3" x-on:click="$dispatch('close')">
                                {{ __('OK') }}
                            </x-primary-button>
                        </div>
                    </div>
                </x-modal>
            </div>
        </div>
    </div>
    <br>
</x-app-layout>
