<header class="fixed w-full">
    <nav class="bg-white border-gray-200 py-2.5 dark:bg-gray-900 shadow-md">
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
            <a href="/" class="flex items-center">
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Менеджер задач</span>
            </a>

            <div class="flex items-center lg:order-2">
                <a href="/logout" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Выход
                </a>

                <form id="logout-form" action="/logout" method="POST"
                      style="display: none;">
                    <input type="hidden" name="_token" value="ddcMguXc21b9VzJ2rIZONxhiPHr8pNybV80mPMuP"
                           autocomplete="off"></form>
            </div>

            <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1">
                <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                    <li>
                        <a href="/tasks"
                           class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0">
                            Задачи </a>
                    </li>
                    <li>
                        <a href="/task_statuses"
                           class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0">
                            Статусы </a>
                    </li>
                    <li>
                        <a href="/labels"
                           class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0">
                            Метки </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

