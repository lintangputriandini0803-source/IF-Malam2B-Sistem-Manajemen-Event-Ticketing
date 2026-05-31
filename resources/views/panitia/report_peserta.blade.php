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
                    {{-- Tambahkan logic loop event Anda di sini --}}
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
                <input type="text" name="search" placeholder="Cari..." 
                       class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                       value="{{ request('search') }}">
            </div>

            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm hover:bg-black transition-colors">
                Terapkan
            </button>

            <a href="{{ route('panitia.export-excel', request()->all()) }}" 
               class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition-all text-sm font-medium">
                <i class="fas fa-file-excel"></i> Ekspor Excel
            </a>
        </form>
    </div>

    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <span>Show</span>
        <select class="border border-gray-300 rounded px-2 py-1">
            <option>10</option>
            <option>25</option>
            <option>50</option>
        </select>
        <span>entries</span>
    </div>

    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="p-3 text-left font-semibold">No. Invoice</th>
                    <th class="p-3 text-left font-semibold">Nama Peserta</th>
                    <th class="p-3 text-left font-semibold">Email</th>
                    <th class="p-3 text-left font-semibold">Detail Tiket</th>
                    <th class="p-3 text-left font-semibold">Total</th>
                    <th class="p-3 text-left text-center font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($peserta as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-3 font-medium text-blue-600">{{ is_array($p) ? ($p['reg_number'] ?? '-') : $p->reg_number }}</td>
                    <td class="p-3 text-gray-800 font-semibold">{{ is_array($p) ? ($p['name'] ?? '-') : $p->name }}</td>
                    <td class="p-3 text-gray-500">{{ is_array($p) ? ($p['email'] ?? '-') : $p->email }}</td>
                    <td class="p-3 text-gray-600">
                        @php $details = is_array($p) ? ($p['details'] ?? []) : $p->details; @endphp
                        @foreach($details as $item)
                            <div class="text-xs">{{ is_array($item) ? ($item['ticket_type']['name'] ?? 'Tiket') : ($item->ticketType->name ?? 'Tiket') }} ({{ is_array($item) ? ($item['quantity'] ?? 0) : $item->quantity }}x)</div>
                        @endforeach
                    </td>
                    <td class="p-3 font-bold text-gray-900">Rp {{ number_format(is_array($p) ? ($p['total_price'] ?? 0) : $p->total_price, 0, ',', '.') }}</td>
                    <td class="p-3 text-center">
                        @php $status = is_array($p) ? ($p['status'] ?? 'pending') : $p->status; @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $status == 'confirmed' || $status == 'LUNAS' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-gray-400">Tidak ada data ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-sm text-gray-500">
            Showing {{ $peserta->firstItem() ?? 0 }} to {{ $peserta->lastItem() ?? 0 }} of {{ $peserta->total() ?? 0 }} entries
        </div>
        <div class="pagination-container">{{ $peserta->appends(request()->all())->links() }}</div>
    </div>
</div>
@endsection