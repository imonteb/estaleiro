<x-layouts.app.free :title="'Equipas Diárias'" :header="'Equipas Diárias'">


    <div class="mt-10">
        {{-- Aquí se mostrarán las equipas diárias para el día publicado --}}
        @if($publishedDay)
            @livewire('operaciones.published-daily-teams', ['date' => $publishedDay->date])
        @else
            <div class="text-gray-500">No hay día publicado. Consulte con el administrador.</div>
        @endif
    </div>



</x-layouts.app.free>
