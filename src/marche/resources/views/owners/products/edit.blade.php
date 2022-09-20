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
                    <x-flash-message status="session('status')"></x-flash-message>
                    <form method="POST" action="{{ route('owners.products.update', ['product' => $product->id]) }}">
                        @csrf
                        @method('put')
                        <div class="-m-2">
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">商品名 *必須</label>
                                    <input type="text" id="name" name="name" value="{{ $product->name }}"
                                        required
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="information" class="leading-7 text-sm text-gray-600">商品情報 *必須</label>
                                    <textarea type="text" id="information" name="information" required rows="10"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $product->information }}</textarea>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="price" class="leading-7 text-sm text-gray-600">価格 *必須</label>
                                    <input type="number" id="price" name="price" required
                                        value="{{ $product->price }}"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="sort_order" class="leading-7 text-sm text-gray-600">表示順</label>
                                    <input type="number" id="sort_order" name="sort_order" required
                                        value="{{ $product->sort_order }}"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="current_quantity" class="leading-7 text-sm text-gray-600">現在の在庫</label>
                                    <input type="hidden" id="current_quantity" name="current_quantity" required
                                        value="{{ $quantity }}">
                                    <div
                                        class="bg-gray-100 bg-opacity-50 rounded text-base outline-none text-gray-700 py-1 px-3 leading-8">
                                        {{ $quantity }}</div>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative flex justify-around">
                                    <div>
                                        <label>
                                            <input type="radio" name="type"
                                                value="{{ \ProductConstant::PRODUCT_LIST['add'] }}" checked
                                                class="mr-2">追加
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio" name="type"
                                                value="{{ \ProductConstant::PRODUCT_LIST['reduce'] }}" class="mr-2">削減
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="quantity" class="leading-7 text-sm text-gray-600">数量 ※必須</label>
                                    <input type="number" step="10" min="0" max="99" id="quantity"
                                        name="quantity" required value="0"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <span class="text-sm">0~99の範囲で入力して下さい</span>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="shop_id" class="leading-7 text-sm text-gray-600">販売する店舗</label>
                                    <select
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                        id="shop_id" name="shop_id">
                                        @foreach ($shops as $shop)
                                            <option value="{{ $shop->id }}"
                                                @if ($shop->id === $product->shop_id) selected @endif>
                                                {{ $shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="category" class="leading-7 text-sm text-gray-600">販売する店舗</label>
                                    <select
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                        id="category" name="category">
                                        @foreach ($categories as $category)
                                            <optgroup label="{{ $category->name }}">
                                                @foreach ($category->secondary as $secondary)
                                                    <option value="{{ $secondary->id }}"
                                                        @if ($secondary->id === $product->secondary_category_id) selected @endif>
                                                        {{ $secondary->name }}
                                                    </option>
                                                @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <x-select-image :images="$images" name="image1" currendId="{{ $product->image1 }}"
                                currentImage="{{ $product->imageFirst->filename ?? '' }}" />
                            <x-select-image :images="$images" name="image2" currendId="{{ $product->image2 }}"
                                currentImage="{{ $product->imageSecond->filename ?? '' }}" />
                            <x-select-image :images="$images" name="image3" currendId="{{ $product->image3 }}"
                                currentImage="{{ $product->imageThird->filename ?? '' }}" />
                            <x-select-image :images="$images" name="image4" currendId="{{ $product->image4 }}"
                                currentImage="{{ $product->imageForth->filename ?? '' }}" />
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative flex justify-around">
                                    <div>
                                        <label>
                                            <input type="radio" name="is_selling" value="1" class="mr-2"
                                                @if ($product->is_selling === 1) { checked } @endif>販売中
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio" name="is_selling" value="0" class="mr-2"
                                                @if ($product->is_selling === 0) { checked } @endif>停止中
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 w-full mt-4 flex justify-around">
                                <button onclick="location.href='{{ route('owners.products.index') }}'" type="button"
                                    class="flex mx-auto text-gray-50 bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">戻る</button>
                                <button type="submit"
                                    class="flex mx-auto text-white bg-purple-500 border-0 py-2 px-8 focus:outline-none hover:bg-purple-600 rounded text-lg">更新</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete_{{ $product->id }}" method="POST"
                        action="{{ route('owners.products.destroy', ['product' => $product->id]) }}">
                        @csrf
                        @method('delete')
                        <div class="p-2 w-full mt-4 flex justify-around">
                            <button type="button" id="delete" data-id="{{ $product->id }}"
                                class="flex mx-auto text-white bg-red-500 border-0 py-2 md:px-6 focus:outline-none hover:bg-red-600 rounded ">削除</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/asset/delete-alert.js', 'resources/js/asset/micromodal/view-image.js'])
</x-app-layout>
