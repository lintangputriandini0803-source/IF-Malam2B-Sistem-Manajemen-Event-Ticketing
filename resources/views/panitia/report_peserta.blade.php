<div class="p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Report Daftar Peserta</h2>

    <table class="w-full border-collapse mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-3 text-left">Nama Peserta</th>
                <th class="border p-3 text-left">Email</th>
                <th class="border p-3 text-left">Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta as $p)
            <tr>
                <td class="border p-3">{{ $p['nama'] }}</td>
                <td class="border p-3">{{ $p['email'] }}</td>
                <td class="border p-3">
                    <span class="px-2 py-1 rounded text-white {{ $p['status'] == 'Lunas' ? 'bg-green-500' : 'bg-yellow-500' }}">
                        {{ $p['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex gap-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Export PDF (Print)
        </button>
        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Export Excel
        </button>
    </div>
</div>