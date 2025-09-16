<div>
    <x-layouts.app.free :title="'Página não encontrada'" :header="'Erro 404'">
        <div class="text-center py-20">
            <h1 class="text-6xl font-bold text-blue-800 dark:text-blue-300">404</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                A página que você procura não foi encontrada.
            </p>
            <a href="{{ route('home') }}"
                class="mt-6 inline-block bg-blue-700 hover:bg-blue-800 text-white text-sm px-4 py-2 rounded">
                Voltar para o início
            </a>
        </div>
    </x-layouts.app.free>
</div>
