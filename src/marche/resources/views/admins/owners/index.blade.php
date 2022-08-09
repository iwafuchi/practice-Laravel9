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
                    Eloquent:
                    <div class="flex">
                        @foreach ($eAll as $eOwner)
                            <div class="flex-1">
                                {{ $eOwner->name }}
                                {{ $eOwner->created_at->diffForHumans() }}
                            </div>
                        @endforeach
                    </div>
                    <br>
                    QueryBuilder:
                    <div class="flex">
                        @foreach ($qGet as $qOwner)
                            <div class="flex-1">
                                {{ $qOwner->name }}
                                {{ Carbon\Carbon::parse($qOwner->created_at)->diffForHumans() }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
