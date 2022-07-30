<x-tests.app>
    <x-slot name="header">ヘッダー2</x-slot>
    <x-slot name="span">span2</x-slot>
    <x-slot name="slot">
        コンポーネントテスト2
        <x-tests.card title="タイトル2" content="本文2" :message="$message"></x-tests.card>
        <x-test-class-base classBaseMessage="メッセージ"></x-test-class-base>
        <x-test-class-base classBaseMessage="メッセージ" num1=10 num2=10></x-test-class-base>
    </x-slot>
</x-tests.app>
