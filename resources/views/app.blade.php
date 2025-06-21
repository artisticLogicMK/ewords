<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <meta name="description" content="We're building a stage for Nigeria's boldest voices. EchoWords is a platform where writers and spoken word artists compete, connect, and get recognized. Wether you're seasoned or starting out, your voice deserves to be heard."/>

    @if(isset($page['props']['ogMeta']))
        <meta property="og:title" content="{{ $page['props']['ogMeta']['title'] }}">
        @if(isset($page['props']['ogMeta']['description']))
        <meta property="og:description" content="{{ $page['props']['ogMeta']['description'] }}">
        @endif
        <meta property="og:image" content="{{ $page['props']['ogMeta']['image'] }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $page['props']['ogMeta']['image'] }}">
    @endif


    @if(isset($page['props']['jsonLd']) && is_array($page['props']['jsonLd']))
        @foreach($page['props']['jsonLd'] as $ld)
            <script type="application/ld+json">
                {!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
        @endforeach
    @elseif(isset($page['props']['jsonLd']))
        <script type="application/ld+json">
            {!! json_encode($page['props']['jsonLd'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif


        <link rel="canonical" href="{{ url()->current() }}" />

        <link rel="icon" href="/favicon.ico" sizes="any">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">

        @routes
        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
