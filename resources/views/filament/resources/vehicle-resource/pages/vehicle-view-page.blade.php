<x-filament-panels::page>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class=" grid grid-cols-1 md:grid-cols-3 gap-2">
        @foreach ($this->vehicles as $vehicle)
       <div class="max-w-sm bg-blue-900 border border-gray-500 rounded-lg shadow-sm dark:bg-gray-800 dark:border-white/20">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 h-12">
                <h5 class="text-xl font-semibold tracking-tight text-yellow-400 dark:text-yellow-500">
                     {{ $vehicle->car_plate }}
                </h5>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $vehicle->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- Imagen -->
            <div class="h-48 bg-white overflow-hidden">
                @if($vehicle->image_url)
                <img class="w-full h-full object-cover" src="{{ asset('storage/' . $vehicle->image_url) }}"
                    alt="{{ $vehicle->model }}" />
                @else
                <div class="flex justify-center items-center h-full text-gray-500 text-sm">Sem imagem</div>
                @endif
            </div>

            <div>
                <h5 class="mb-2 px-2 text-2xl font-bold tracking-tight bg-yellow-600 text-white dark:text-white">{{
                    $vehicle->vehicleBrand->name }} - {{ $vehicle->model }}
                </h5>

                <!-- Información como tabla -->
                <div class="px-4 py-2 space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Matrícula:</span>
                        <span class="text-blue-300">{{ $vehicle->car_plate }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Tipo:</span>
                        <span class="text-blue-300">{{ $vehicle->type }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Cartão combustível:</span>
                        <span class="text-blue-300">{{ $vehicle->fuel_card_number }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">PIN combustível:</span>
                        <span class="text-blue-300">{{ $vehicle->fuel_card_pin }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Seguro:</span>
                        <span class="text-blue-300">{{ $vehicle->insurance_name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Validade seguro:</span>
                        <span class="text-blue-300">
                            {{ optional($vehicle->insurance_validity_date)->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Última inspeção:</span>
                        <span class="text-blue-300">
                            {{ optional($vehicle->last_vehicle_inspection_date)->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Condição:</span>
                        <span class="text-blue-300">{{ $vehicle->vehicle_condition }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-400">Atribuído:</span>
                        <span class="text-blue-300">{{ $vehicle->assigned ? 'Sim' : 'Não' }}</span>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ \App\Filament\Resources\VehicleResource::getUrl('edit', ['record' => $vehicle->id]) }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Editar
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</x-filament-panels::page>
