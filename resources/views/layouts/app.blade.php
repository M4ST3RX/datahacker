<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body style="@if(!isset($os->background_image) && isset($os->background_color)) background-color: {{ $os->background_color }}; @endif;">
    @auth
        <style>
        .menu-selected {
            background-color: {{ $os->menu_selected_color }};
        }
        .start-menu {
            color: {{ $os->start_menu_color }};
        }
        </style>
    @endauth
    <div id="app">
        @auth
         <div class="game-header" style="width: 100%; font-size: 12px; color: white; background-color: #262626; border-bottom: 4px solid #424242;">
             <div class="row" style="width:100%; padding:2px 0 2px 0; margin:0;">
                <div class="col-md-4" style="padding-left: 15px;">
                    Target IP
                </div>
                 <div class="col-md-4" style="text-align: center;">
                     {{ $os->name . " - " . \Illuminate\Support\Facades\Auth::user()->username }}
                 </div>
                 <div class="col-md-4" style="text-align: right; padding-right: 15px;">
                     {{ session('computer_ip') }}
                 </div>
             </div>
         </div>
        @endauth

        @yield('content')
        @auth
            <tray :os="{{ $os }}"></tray>
        @endauth
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
