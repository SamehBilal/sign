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

                        <div id="data-section" class="mt-6">
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
                                                {{ __('Name') }}
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Date') }}
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Information') }}
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Status') }}
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Esign') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataTableBody" class="bg-white divide-y divide-gray-200">
                                        @foreach ($agreements['userAgreementList'] as $index => $agreement)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
=                                                <td class="px-6 py-4 whitespace-nowrap">{{ $agreement['name'] }}</td>
=                                                <td class="px-6 py-4 whitespace-nowrap">{{ $agreement['displayDate'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $agreement['displayParticipantSetInfos'][0]['displayUserSetMemberInfos'][0]['fullName'] }} ({{ $agreement['displayParticipantSetInfos'][0]['displayUserSetMemberInfos'][0]['email'] }})
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $agreement['status'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $agreement['esign'] ? 'True':'False' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
