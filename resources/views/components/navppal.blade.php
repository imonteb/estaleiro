<div>
    <nav class="bg-blue-700 border-gray-200 dark:bg-gray-900 ">
        <!--   -->
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-1.5">
            <!-- Logo  -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse ">

                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                <span class="text-amber-500 self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
                    Estaleiro C016
                </span>
            </a>
            <div class="space-x-6 rtl:space-x-reverse ">

                <div class="text-white pt-1">
                    <div class="max-w-screen-xl  mx-auto">
                        <div class="flex items-center">
                            <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                                <li>
                                    <a href="{{ route('home') }}" class="text-white dark:text-white hover:underline"
                                        aria-current="page">Iniciar</a>
                                </li>

                                <li>
                                    <a href="{{ route('operaciones') }}" class="text-white dark:text-white hover:underline">Equipas Diárias</a>
                                </li>
                                <li>
                                    <a href="{{ route('technicalinfo') }}" class="text-white dark:text-white hover:underline">Informações</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white dark:text-white hover:underline">
                                        <a href="#" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">
                                            <div class="flex flex-wrap justify-between items-end mx-auto ">
                                                <div class="flex items-center">
                                                    @if (Route::has('login'))
                                                    <div class="">
                                                        @auth
                                                        <a href="{{ url('/admin') }}"
                                                            class="font-semibold text-gray-100 hover:text-amber-500
                                                             dark:text-gray-400 dark:hover:text-amber-500 focus:outline
                                                            focus:outline-2 focus:rounded-sm focus:outline-red-500 text-sm">
                                                            Painel
                                                        </a>
                                                        @else
                                                        <a href="{{ route('filament.admin.auth.login') }}"
                                                            class="font-semibold text-gray-100 hover:text-amber-500
                                                             dark:text-gray-400 dark:hover:text-amber-500 focus:outline
                                                             focus:outline-2 focus:rounded-sm focus:outline-red-500 text-sm">
                                                            Liga-te
                                                        </a>

                                                        {{-- @if (Route::has('register')) -----------------------
                                                        <a href="{{ route('register') }}"
                                                            class="ml-4 font-semibold text-gray-100 hover:text-amber-500
                                                             dark:text-gray-400 dark:hover:text-amber-500 focus:outline
                                                            focus:outline-2 focus:rounded-sm focus:outline-red-500 text-sm">
                                                            Register
                                                        </a>
                                                        @endif --}}
                                                        @endauth
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </a></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </nav>
</div>
