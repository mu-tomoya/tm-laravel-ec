@php
    
    $s3_url = env('AWS_S3_URL');
    if ($type === 'shops') {
        $path = '/shops/';
    } elseif ($type === 'products') {
        $path = '/products/';
    }
    
@endphp

<div>
    @if (empty($filename))
        <img src="{{ asset('images/no_image.jpg') }}">
    @else
        <img src="{{ $s3_url . $path . $filename }}">
    @endif
</div>
