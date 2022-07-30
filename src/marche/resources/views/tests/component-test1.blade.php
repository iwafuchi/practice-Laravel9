<x-tests.app>
    <x-slot name="header">ヘッダー1</x-slot>
    <x-slot name="span">span1</x-slot>
    <x-slot name="slot">
        コンポーネントテスト1
        <x-tests.card title="タイトル1" content="本文1" :message="$message"></x-tests.card>
        <x-tests.card title="タイトル1-1" content="本文1-1" :message="$message . '-1'"></x-tests.card>
        <x-tests.card title="タイトル1-2"></x-tests.card>
    </x-slot>
</x-tests.app>
