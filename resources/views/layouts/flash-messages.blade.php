@if (session('success'))
    <meta name="success-message" content="{{ session('success') }}">
@endif

@if ($errors->any())
    <meta name="error-message" content="{{ $errors->first() }}">
@endif