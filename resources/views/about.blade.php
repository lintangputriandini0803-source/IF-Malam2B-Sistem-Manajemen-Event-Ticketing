<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - {{ $data['nama_project'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">

    <div class="max-w-5xl mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-purple-900 mb-4">{{ $data['nama_project'] }}</h1>
            <p class="text-xl text-gray-600">{{ $data['deskripsi'] }}</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-16">
            @foreach($data['layanan'] as $item)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-purple-800 mb-2">{{ $item['jenis'] }}</h3>
                <p class="text-gray-600 text-sm">{{ $item['fitur'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold mb-8 text-center">Tim Pengembang</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($data['tim'] as $person)
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-purple-700 font-bold text-xl">{{ substr($person['nama'], 0, 1) }}</span>
                    </div>
                    <h4 class="font-bold">{{ $person['nama'] }}</h4>
                    <p class="text-sm text-gray-500">{{ $person['nim'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-12 text-center text-gray-400">
            <p>Dikembangkan di: {{ $data['lokasi'] }}</p>
        </div>
    </div>

</body>
</html>