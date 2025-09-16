{{-- <x-layouts.app.free :title="__('Informações técnicas')">
    <div>
        <h3 class="text-5xl">Informações técnicas</h3>
        @livewire('operations.chart')
    </div>
</x-layouts.app.free> --}}
<x-layouts.app.free :title="'Informações Técnicas'" :header="'Informações Técnicas'">
    <div class="space-y-4">
        <p class="text-gray-700 dark:text-gray-300">
            Nesta seção você poderá consultar esquemas técnicos, diagramas, manuais operacionais e outros documentos essenciais.
        </p>

        <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
            <h2 class="text-lg font-semibold text-blue-700 dark:text-blue-300 mb-2">Documentação mais recente</h2>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                <li>Manual de segurança operacional (v2.1)</li>
                <li>Esquema elétrico da ponte grua</li>
                <li>Plano de evacuação e contingência</li>
            </ul>
        </div>
    </div>
</x-layouts.app.free>



