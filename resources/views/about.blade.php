<html>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th> [cite: 49]
                <th>Nama</th> [cite: 50]
                <th>Harga</th> [cite: 51]
            </tr>
        </thead>
        <tbody>
            @foreach($data as $dataku) 
            <tr>
                <td>{{ $dataku['id'] }}</td> 
                <td>{{ $dataku['nama'] }}</td> 
                <td>{{ $dataku['harga'] }}</td> 
            </tr>
            @endforeach [cite: 59]
        </tbody>
    </table>
</html>