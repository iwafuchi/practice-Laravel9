<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="md:flex md:justify-around">
                        {{-- {{ dd($product) }} --}}
                        <div class="md:w-1/2">
                            {{-- <x-thumbnail :filename="$product->imageFirst->filename ?? ''" type="products" /> --}}
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        @if ($product->imageFirst->filename !== null)
                                            <img
                                                src="{{ asset('storage/products/' . $product->imageFirst->filename) }}">
                                        @else
                                            <img src="">
                                        @endif
                                    </div>
                                    <div class="swiper-slide">
                                        @if ($product->imageSecond->filename !== null)
                                            <img
                                                src="{{ asset('storage/products/' . $product->imageSecond->filename) }}">
                                        @else
                                            <img src="">
                                        @endif
                                    </div>
                                    <div class="swiper-slide">
                                        @if ($product->imageThird->filename !== null)
                                            <img
                                                src="{{ asset('storage/products/' . $product->imageThird->filename) }}">
                                        @else
                                            <img src="">
                                        @endif
                                    </div>
                                    <div class="swiper-slide">
                                        @if ($product->imageForth->filename !== null)
                                            <img
                                                src="{{ asset('storage/products/' . $product->imageForth->filename) }}">
                                        @else
                                            <img src="">
                                        @endif
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-scrollbar"></div>
                            </div>
                        </div>
                        <div class="md:w-1/2 ml-4">
                            <h2 class="text-sm title-font text-gray-500 tracking-widest mb-1">
                                {{ $product->category->name }}
                            </h2>
                            <h1 class="text-gray-900 text-3xl title-font font-medium mb-2">{{ $product->name }}</h1>
                            <p class="leading-relaxed">{{ $product->information }}</p>
                            <div class="flex justify-around items-center">
                                <div>
                                    <span
                                        class="title-font font-medium text-2xl text-gray-900">{{ number_format($product->price) }}</span>
                                    <span class="text-sm text-gray-700">円(税込)</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-3">数量</span>
                                    <div class="relative">
                                        <select
                                            class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10">
                                            <option>SM</option>
                                            <option>M</option>
                                            <option>L</option>
                                            <option>XL</option>
                                        </select>
                                    </div>
                                </div>
                                <button
                                    class="flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">カートに入れる</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/asset/swiper/swiper.js')
</x-app-layout>