<x-tests.app>
    <x-slot name="header">ヘッダー2</x-slot>
    <x-slot name="span">span2</x-slot>
    <x-slot name="slot">
        コンポーネントテスト2
        <x-tests.card title="タイトル2" content="本文2" :message="$message"></x-tests.card>
    </x-slot>
</x-tests.app>
