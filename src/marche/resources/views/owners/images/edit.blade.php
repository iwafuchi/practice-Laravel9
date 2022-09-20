<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form method="POST" action="{{ route('owners.images.update', ['image' => $image->id]) }}">
                        @csrf
                        @method('put')
                        <div class="p-2 w-1/2 mx-auto">
                            <div class="relative">
                                <label for="title" class="leading-7 text-sm text-gray-600">画像タイトル</label>
                                <input type="text" id="title" name="title" value="{{ $image->title }}"
                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-2 w-1/2 mx-auto">
                            <div class="relative">
                                <div class="w-32">
                                    <x-thumbnail :filename="$image->filename" type="products" />
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="p-3 w-1/2 flex justify-center">
                                <button onclick="location.href='{{ route('owners.images.index') }}'" type="button"
                                    class="mx-auto text-gray-50 bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">戻る</button>
                            </div>
                            <div class="p-3 w-1/2 flex justify-center">
                                <button type="submit"
                                    class="mx-auto text-white bg-purple-500 border-0 py-2 px-8 focus:outline-none hover:bg-purple-600 rounded text-lg">更新</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete_{{ $image->id }}" method="POST"
                        action="{{ route('owners.images.destroy', ['image' => $image->id]) }}">
                        @csrf
                        @method('delete')
                        <div class="flex">
                            <div class="p-3 w-1/2">
                            </div>
                            <div class="p-3 w-1/2 flex justify-center">
                                <button type="button" id="delete" data-id="{{ $image->id }}"
                                    class="mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">削除</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    @vite('resources/js/asset/delete-alert.js')
</x-app-layout>
