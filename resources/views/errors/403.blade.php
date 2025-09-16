<div>
    <x-layouts.app.free :title="'Acesso negado'" :header="'403 - Acesso negado'">
        <div class="text-center py-16">
            <h1 class="text-6xl font-bold text-red-600">403</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                Você não tem permissão para acessar esta página.
            </p>
            <a href="{{ route('home') }}"
                class="mt-6 inline-block text-sm px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded">
                Voltar ao início
            </a>
        </div>
    </x-layouts.app.free>
</div>
