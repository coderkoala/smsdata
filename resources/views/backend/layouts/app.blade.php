<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
    @yield('meta')
    <livewire:styles />
    @stack('after-styles')
</head>

<body class="c-app">
    @include('backend.includes.sidebar')

    <div class="c-wrapper c-fixed-components">
        @include('backend.includes.header')
        @include('includes.partials.read-only')
        @include('includes.partials.logged-in-as')
        @include('includes.partials.announcements')

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        @include('includes.partials.messages')
                        @yield('content')
                    </div>
                    <!--fade-in-->
                </div>
                <!--container-fluid-->
            </main>
        </div>
        <!--c-body-->

        @include('backend.includes.footer')
    </div>
    <!--c-wrapper-->

    @stack('before-scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/backend.js') }}"></script>
    <livewire:scripts />
    <style>
        .select2-container {
            min-width: 100% !important;
        }
    </style>
    @stack('before-styles')
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/select2.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/table.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('/js/table.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/select2.js') }}" type="text/javascript"></script>
    <script defer src="{{ asset('/js/custom.js') }}" type="text/javascript"></script>
    @stack('after-scripts')
</body>

</html>
