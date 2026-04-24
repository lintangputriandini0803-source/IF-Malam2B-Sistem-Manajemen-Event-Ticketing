<!DOCTYPE html>
<html>
<head>
    <title>List Barang</title>
</head>
<body>

    <h1>List Barang</h1>

    <ul>
        @foreach ($barangs as $barang)
            <li>{{ $barang['id'] }} - {{ $barang['nama'] }}</li>
        @endforeach
    </ul>

</body>
</html>