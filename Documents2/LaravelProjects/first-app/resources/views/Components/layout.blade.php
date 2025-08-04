
<!DOCTYPE html>

{{ asset('PHP/all.php') }}

@props(['pageName'=>'default page name', 'pageTitle'=>'default title'])

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
   
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        
        <title>Drew's Resources</title>

    </head>

    <body>


        <x-siteHeader>Drew's Resources</x-header>
        
        <x-navbar pageName={{$pageName}}> </x-navbar>

        <x-pageTitle pageTitle={{$pageTitle}}></x-pageTitle>

        {{$slot}}

        <x-footer> </x-footer>
        
    </body>
</html>