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

<body style="@if(!isset($os->background_image)) background-color: {{ $os->background_color }}; @endif; overflow: hidden;">
    <style>
    .menu-selected {
        background-color: {{ $os->menu_selected_color }};
    }
    .start-menu {
        color: {{ $os->start_menu_color }};
    }
    </style>

    <div id="app">
        <computer :os='{{ json_encode($os) }}'>
            <template slot='operating_system' scope="props">
                <terminal :os="props.os"></terminal>
                <task-manager :os="props.os"></task-manager>
                <history :os="props.os"></history>
                <files :os="props.os"></files>

                <tray :os="props.os"></tray>
            </template>
        </computer>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
