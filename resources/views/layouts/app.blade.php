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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Add FontAwesome for heart icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<style>
    .post-container {
        position: relative; /* Ensure positioning context for the buttons */
    }

    .carousel-container button {
        font-size: 2rem; /* Bigger arrows */
        width: 50px; /* Width of the button */
        height: 50px; /* Height of the button */
        background: none; /* No background */
        border: none; /* No border */
        color: white; /* Arrow color */
        z-index: 10; /* Ensure buttons are above images */
    }

    /* Positioning the left button on the middle-left of the image */
    .prevBtn {
        left: 10px; /* Adjust as needed */
    }

    /* Positioning the right button on the middle-right of the image */
    .nextBtn {
        right: 10px; /* Adjust as needed */
    }

    /* Optional: Add a hover effect for better interactivity */
    .carousel-container button:hover {
        color: #ccc; /* Light color on hover */
    }
</style>

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
