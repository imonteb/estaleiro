<div>
    <x-layouts.app.free :title="'Erro interno'" :header="'Erro 500 - Algo deu errado'">
        <div class="text-center py-20">
            <h1 class="text-6xl font-extrabold text-red-700">500</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                Ocorreu um erro inesperado no servidor. A equipa técnica já foi avisada (ou pelo menos deveria ter sido
                👀).
            </p>
            <a href="{{ route('home') }}"
                class="mt-6 inline-block px-4 py-2 text-sm bg-blue-700 hover:bg-blue-800 text-white rounded">
                Voltar à página inicial
            </a>
        </div>
    </x-layouts.app.free>
</div>
