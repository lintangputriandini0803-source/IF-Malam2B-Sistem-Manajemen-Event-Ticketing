<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<link rel="stylesheet" href="{{ asset('styles/style_rafli.css') }}">

<h1>Ini Judul Merah</h1>

<img src="{{ asset('poster/image1.png') }}" alt="">
<img src="{{ asset('poster/image4.png') }}" alt="">

<div class="bg-blue-500 p-4 m-4 rounded-lg text-white">
    Ini pakai Tailwind
</div>
</body>
</html>
