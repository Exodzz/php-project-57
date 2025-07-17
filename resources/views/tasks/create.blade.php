<x-app-layout>

    <div class="py-6">

        <div style="
    width: 1200px;
" class=" mx-auto sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Создать задачу
            </h2>
            <form method="POST" action="{{ route('tasks.store') }}"
                class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @csrf

                {{-- Название --}}
                <div class="mb-2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Имя</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                </div>
                @error('name')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                {{-- Описание --}}
                <div class="mb-2">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание</label>
                    <textarea id="description" name="description"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                </div>
                @error('description')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                {{-- Статус --}}
                <div class="mb-2">
                    <label for="status_id" class="block text-gray-700 text-sm font-bold mb-2">Статус</label>
                    <select name="status_id" id="status_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value=""></option>
                        @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @selected(old('status_id')==$status->id)>
                            {{ $status->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @error('status_id')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                {{-- Исполнитель --}}
                <div class="mb-2">
                    <label for="assigned_to_id" class="block text-gray-700 text-sm font-bold mb-2">Исполнитель</label>
                    <select name="assigned_to_id" id="assigned_to_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value=""></option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_to_id')==$user->id)>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @error('assigned_to_id')
                <div class="text-rose-600">{{ $message }}</div>
                @enderror

                {{-- Метки --}}
                <div class="mb-2">
                    <label for="labels" class="block text-gray-700 text-sm font-bold mb-2">Метки</label>
                    <select multiple name="labels[]" id="labels" size="4"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($labels as $label)
                        <option value="{{ $label->id }}" @if(collect(old('labels', []))->contains($label->id)) selected
                            @endif>
                            {{ $label->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Кнопка --}}
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('messages.Create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
