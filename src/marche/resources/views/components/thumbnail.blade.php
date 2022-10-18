@php
if ($type === 'shops') {
    $path = 'storage/shops/';
}
if ($type === 'products') {
    $path = 'storage/products/';
}
@endphp
<div>
    @if (empty($filename))
        <img src="{{ Vite::asset('resources/images/no_image.jpg') }}">
    @else
        <img src="{{ asset($path . $filename) }}">
    @endif
</div>
