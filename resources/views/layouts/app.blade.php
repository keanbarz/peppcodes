<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>/*Tables+Input Box+Button*/
            /* Define styles for the table */
            table {
                width: 100%;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
                /*border: 0px solid #ddd; /* Add border to the entire table */
                }

            /* Style table headers */
            th {
                padding: 10px;
                min-width: unset;
                border: 1px solid #ddd; /* Add border to table header cells */
                }

            tr:hover {
                background-color: yellow;
                color: red;
                }

            .xx {
                background-color: green;
                color: white;
                min-width: 80px;
                height: 40px;
                padding: 8px;
                font-size: 16px;
                border: 1px solid #ddd;
                border-radius: 10px;
                }
               
        </style>

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
