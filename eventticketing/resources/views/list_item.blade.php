<!DOCTYPE html>
<html>
<head>
    <title>List Item PBL</title>
</head>
<body>
    <h1>Daftar Barang</h1>
    @if(isset($id) && isset($name))
        <p>ID Barang: {{ $id }}</p>
        <p>Nama Barang: {{ $name }}</p>
    @else
        <p>Menampilkan semua item</p>
    @endif
</body>
</html>