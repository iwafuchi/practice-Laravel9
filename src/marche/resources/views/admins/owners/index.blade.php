<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            オーナー一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="text-gray-600 body-font">
                        <div class="container md:px-5 mx-auto">
                            <x-flash-message status="session('status')"></x-flash-message>
                            <div class="flex justify-end mb-4">
                                <button onclick="location.href='{{ route('admins.owners.create') }}'"
                                    class="text-white bg-purple-500 border-0 py-2 md:px-8 focus:outline-none hover:bg-purple-600 rounded text-lg">新規登録</button>
                            </div>
                            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                                <table class="table-auto w-full text-left whitespace-no-wrap">
                                    <thead>
                                        <tr>
                                            <th
                                                class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                                name</th>
                                            <th
                                                class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                                email</th>
                                            <th
                                                class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                                created_at</th>
                                            <th
                                                class="w-32 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">
                                            </th>
                                            <th
                                                class="w-32 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($owners as $owner)
                                            <tr>
                                                <td class="md:px-4 py-3">{{ $owner->name }}</td>
                                                <td class="md:px-4 py-3">{{ $owner->email }}</td>
                                                <td class="md:px-4 py-3">{{ $owner->created_at->diffForHumans() }}</td>
                                                <td class="w-32 text-center">
                                                    <button type="button"
                                                        onclick="location.href='{{ route('admins.owners.edit', ['owner' => $owner->id]) }}'"
                                                        class="flex mx-auto text-white bg-purple-500 border-0 py-2 md:px-6 focus:outline-none hover:bg-purple-600 rounded ">編集</button>
                                                </td>
                                                <form id="delete_{{ $owner->id }}" method="POST"
                                                    action="{{ route('admins.owners.destroy', ['owner' => $owner->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <td class="w-32 text-center">
                                                        <button type="button" data-id="{{ $owner->id }}"
                                                            onclick="deletePost(this)"
                                                            class="flex mx-auto text-white bg-red-500 border-0 py-2 md:px-6 focus:outline-none hover:bg-red-600 rounded ">削除</button>
                                                    </td>
                                                </form>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $owners->links() }}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <script src="{{ asset('/js/test.js') }}"></script>
</x-app-layout>
