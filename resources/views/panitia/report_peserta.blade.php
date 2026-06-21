@extends('layouts.panitia')
@section('title', 'Report Peserta')
@section('page-title', 'Report Peserta')

@section('content')
<div class="p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">REPORT PESERTA</h2>

    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
        <form action="{{ route('panitia.report_peserta') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-600 uppercase">Event</label>
                <select name="event_id" class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Event</option>

                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->name ?? $event->title ?? $event->nama ?? 'Nama Event Tidak Ditemukan' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-600 uppercase">Rentang Tanggal</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-gray-300 rounded px-2 py-2 text-sm" />
                    <span class="text-gray-500">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-gray-300 rounded px-2 py-2 text-sm" />
                </div>
            </div>

            <div class="flex flex-col gap-1 flex-grow">
                <label class="text-xs font-bold text-gray-600 uppercase">Search</label>
                <input type="text" name="search" placeholder="Cari nama, email..."
                       class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                       value="{{ request('search') }}">
            </div>

            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm hover:bg-black transition-colors">Terapkan</button>

<a href="{{ route('panitia.report.excel', request()->all()) }}"
               class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition-all text-sm font-medium">
                📥 Ekspor Excel
            </a>
        </form>
    </div>

    <div class="overflow-x-auto border rounded-lg shadow-sm">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr class="text-xs uppercase text-gray-500 text-left">
                    <th class="p-4">Reg. Number</th>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Nama Pembeli</th>
                    <th class="p-4">NIK/NIM</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">No. HP</th>
                    <th class="p-4">Event</th>
                    <th class="p-4">Total</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($peserta as $p)
                <tr class="text-sm hover:bg-gray-50">
                    <td class="p-4 font-semibold text-purple-700">{{ $p->reg_number ?? '-' }}</td>
                    <td class="p-4 text-gray-600">{{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}</td>
                    <td class="p-4 font-medium text-gray-800">{{ $p->name ?? '-' }}</td>
                    <td class="p-4 text-gray-600">{{ $p->nim ?? '-' }}</td>
                    <td class="p-4 text-gray-600">{{ $p->email ?? '-' }}</td>
                    <td class="p-4 text-gray-600">{{ $p->phone ?? '-' }}</td>
                    <td class="p-4 text-gray-600">{{ $p->event->title ?? '-' }}</td>
                    <td class="p-4 font-bold text-gray-900">Rp {{ number_format($p->total_price ?? 0, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        @php $status = $p->status ?? 'pending'; @endphp
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $status == 'LUNAS' || $status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="p-8 text-center text-gray-400">Tidak ada data ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $peserta->appends(request()->all())->links() }}
    </div>
</div>
@endsection
