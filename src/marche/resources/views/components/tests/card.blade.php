@props(['title' => 'タイトルを設定して下さい', 'content' => '本文を設定して下さい', 'message' => 'メッセージを設定して下さい']);

<div {{ $attributes->merge([
    'class' => 'border-2 shadow-md w-1/4 p-2',
]) }} class="border-2 shadow-md w-1/4 p-2">
    <div>{{ $title }}</div>
    <div>画像</div>
    <div>{{ $content }}</div>
    <div>{{ $message }}</div>
</div>
