@extends('layouts.panitia')
@section('title', 'Report Peserta')
@section('page-title', 'Report Peserta')

@section('content')
<div class="p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">REPORT PESERTA</h2>

    <!-- Show Entries & Ekspor -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <span>Show</span>
            <select class="border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span>entries</span>
        </div>
        
        <div class="flex gap-2">
            <!-- Form Search -->
            <form action="{{ route('panitia.report_peserta') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Cari..." 
                       class="border border-gray-300 rounded-lg py-2 px-4 text-sm focus:ring-2 focus:ring-blue-500"
                       value="{{ request('search') }}">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
            </form>

            <!-- Tombol Ekspor -->
            <a href="{{ route('panitia.export-excel', request()->all()) }}" 
               class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition-all text-sm font-medium">
                <i class="fas fa-file-excel"></i> 📥 Ekspor Excel
            </a>
        </div>
    </div>

    <!-- Table Section -->
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
                    <td class="p-3 font-medium text-blue-600">
                        {{ is_array($p) ? ($p['reg_number'] ?? '-') : $p->reg_number }}
                    </td>
                    <td class="p-3 text-gray-800 font-semibold">{{ is_array($p) ? ($p['name'] ?? '-') : $p->name }}</td>
                    <td class="p-3 text-gray-500">{{ is_array($p) ? ($p['email'] ?? '-') : $p->email }}</td>
                    <td class="p-3 text-gray-600">
                        @php $details = is_array($p) ? ($p['details'] ?? []) : $p->details; @endphp
                        @foreach($details as $item)
                            <div class="text-xs">
                                {{ is_array($item) ? ($item['ticket_type']['name'] ?? 'Tiket') : ($item->ticketType->name ?? 'Tiket') }} 
                                ({{ is_array($item) ? ($item['quantity'] ?? 0) : $item->quantity }}x)
                            </div>
                        @endforeach
                    </td>
                    <td class="p-3 font-bold text-gray-900">
                        Rp {{ number_format(is_array($p) ? ($p['total_price'] ?? 0) : $p->total_price, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-center">
                        @php $status = is_array($p) ? ($p['status'] ?? 'pending') : $p->status; @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $status == 'confirmed' || $status == 'LUNAS' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination & Info -->
    <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-sm text-gray-500">
            Showing {{ $peserta->firstItem() ?? 0 }} to {{ $peserta->lastItem() ?? 0 }} of {{ $peserta->total() ?? 0 }} entries
        </div>
        
        <div class="pagination-container">
            {{-- Navigasi Next/Prev --}}
            {{ $peserta->appends(request()->all())->links() }}
        </div>
    </div>
</div>
@endsection