<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight text-gray-900 mb-8">
            {{ __('Задачи') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="flex flex-wrap gap-2 mb-6">
                <select name="status_id" class="border rounded px-3 py-2">
                    <option value="">Статус</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>{{ $status->name }}</option>
                    @endforeach
                </select>
                <select name="author_id" class="border rounded px-3 py-2">
                    <option value="">Автор</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>{{ $author->name }}</option>
                    @endforeach
                </select>
                <select name="executor_id" class="border rounded px-3 py-2">
                    <option value="">Исполнитель</option>
                    @foreach($executors as $executor)
                        <option value="{{ $executor->id }}" @selected(request('executor_id') == $executor->id)>{{ $executor->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Применить</button>
            </form>

            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Статус</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Имя</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Автор</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Исполнитель</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Дата создания</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tasks as $task)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $task->id }}</td>
                                <td class="px-4 py-2 text-sm">{{ $task->status->name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:underline">
                                        {{ $task->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 text-sm">{{ $task->author->name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $task->executor->name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $task->created_at->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>