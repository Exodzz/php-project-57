<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight text-gray-900 mb-8">
            {{ __('Задачи') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div style="width: 1200px;max-width:100%"  class=" mx-auto sm:px-6 lg:px-8">
        <div class="w-full flex items-center" style="justify-content: space-between;">
            <form method="GET" class="flex flex-wrap gap-2 mb-6">
                <select id="filter[status_id]" name="filter[status_id]" class="border rounded px-3 py-2">
                    <option value="">Статус</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @selected(request('filter.status_id') == $status->id)>{{ $status->name }}</option>
                    @endforeach
                </select>
                <select  id="filter[created_by_id]" name="filter[created_by_id]" class="border rounded px-3 py-2">
                    <option value="">Автор</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @selected(request('filter.created_by_id') == $author->id)>{{ $author->name }}</option>
                    @endforeach
                </select>
                <select  id="filter[assigned_to_id]" name="filter[assigned_to_id]" class="border rounded px-3 py-2">
                    <option value="">Исполнитель</option>
                    @foreach($executors as $executor)
                        <option value="{{ $executor->id }}" @selected(request('filter.assigned_to_id') == $executor->id)>{{ $executor->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Применить</button>
            </form>

            @auth
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                    {{__('messages.Add')}}
                </a>
            @endauth
        </div>
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
                            @auth
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Действие</th>
                            @endauth
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
                                <td class="px-4 py-2 text-sm">{{ $task->creator->name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $task->assignee->name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $task->created_at->format('d.m.Y') }}</td>
                                @auth
                                <td class="px-4 py-2 text-sm">
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline mr-2">
                                        {{__('messages.Edit')}}
                                    </a>
                                    @if(Auth::id() === $task->created_by_id)
                                        <form id="delete-form-{{ $task->id }}" method="POST" action="{{ route('tasks.destroy', $task) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="#"
                                           onclick="if(confirm('Вы уверены?'))document.getElementById('delete-form-{{ $task->id }}').submit(); return false;"
                                           class="text-red-600 hover:text-red-800 hover:underline">
                                            {{__('messages.Delete')}}
                                        </a>
                                    @endif
                                </td>
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</x-app-layout>
