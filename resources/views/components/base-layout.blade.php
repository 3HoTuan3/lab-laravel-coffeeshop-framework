@php
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Session;

    $theme = Session::get('theme', 'theme-light');
    $locale = App::currentLocale();
    $bg_background = $theme === 'theme-light'
        ? 'bg-gradient-to-r from-rose-100 to-teal-100'
        : 'bg-background';
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" class="{{ $theme }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $page_title ?? 'Coffee Shops' }}</title>

    @vite('resources/css/app.css')
    @livewireStyles
    @stack('styles')
</head>

<body class="{{ $bg_background }} relative">

@yield('content')

@livewireScripts
<script>window.theme = '{{ $theme }}'</script>
@vite('resources/js/app.js')
@stack('scripts')
@yield('components')
</body>

</html>
