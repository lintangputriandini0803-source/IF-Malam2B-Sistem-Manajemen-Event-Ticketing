@extends('layouts.public')

@section('title', 'Semua Event - SIMETIX')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="bg-gradient-to-r from-[#4a005a] to-[#6B0080] py-12 px-5 mb-8">
        <div class="max-w-screen-xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Discover Events</h1>
            <p class="text-white/60 text-sm">Temukan event menarik di lingkungan Polibatam</p>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="max-w-xl mx-auto mb-8 px-4 relative">
        <form method="GET" action="{{ route('homepage') }}" id="search-form-hp">
            <input type="hidden" name="category" id="category-input-hp" value="{{ request('category') }}">
            <div class="search-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="search-input"
                       placeholder="Cari event, lokasi...">
                <div class="search-divider"></div>
                <button type="button" id="filter-btn-hp" class="btn-filter" title="Filter Kategori">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10.83 5a3.001 3.001 0 0 0-5.66 0H4a1 1 0 1 0 0 2h1.17a3.001 3.001 0 0 0 5.66 0H20a1 1 0 1 0 0-2h-9.17ZM4 11h9.17a3.001 3.001 0 0 1 5.66 0H20a1 1 0 1 1 0 2h-1.17a3.001 3.001 0 0 1-5.66 0H4a1 1 0 1 1 0-2Zm1.17 6H4a1 1 0 1 0 0 2h1.17a3.001 3.001 0 0 0 5.66 0H20a1 1 0 1 0 0-2h-9.17a3.001 3.001 0 0 0-5.66 0Z"/>
                    </svg>
                    @if(request('category'))
                    <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-[#6B0080] rounded-full"></span>
                    @endif
                </button>
                <button type="submit" class="btn-search">Cari</button>
            </div>
        </form>

        {{-- Dropdown Kategori --}}
        <div id="category-dropdown-hp"
             class="hidden absolute left-4 right-4 top-full mt-2 bg-white rounded-2xl shadow-xl border border-purple-50 z-50 p-3">
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2 px-1 tracking-wider">Pilih Kategori</p>
            <div class="grid grid-cols-2 gap-1">
                <button type="button" onclick="selectCategoryHp('')"
                        class="text-left px-3 py-2 rounded-xl text-sm transition font-medium
                               {{ request('category') == '' ? 'bg-purple-100 text-[#6B0080]' : 'text-gray-600 hover:bg-purple-50 hover:text-[#6B0080]' }}">
                    Semua
                </button>
                @foreach($categories as $cat)
                <button type="button" onclick="selectCategoryHp('{{ $cat->id }}')"
                        class="text-left px-3 py-2 rounded-xl text-sm transition font-medium
                               {{ request('category') == $cat->id ? 'bg-purple-100 text-[#6B0080]' : 'text-gray-600 hover:bg-purple-50 hover:text-[#6B0080]' }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RESULT INFO --}}
    @if(request('search') || request('category'))
    <p class="text-center text-gray-500 mb-6 text-sm">
        @if(request('search'))Hasil untuk: <strong class="text-gray-700">"{{ request('search') }}"</strong>@endif
        @if(request('category'))
            @php $activeCat = $categories->firstWhere('id', request('category')); @endphp
            @if($activeCat) — Kategori: <strong class="text-gray-700">{{ $activeCat->name }}</strong>@endif
        @endif
        <span class="text-gray-400">({{ $events->total() }} event)</span>
        <a href="{{ route('homepage') }}" class="text-[#6B0080] ml-2 hover:underline font-medium">Reset</a>
    </p>
    @else
    <div class="flex justify-center mb-6">
        <span class="section-label">Semua Event di Polibatam</span>
    </div>
    @endif

    {{-- GRID EVENT --}}
    <div class="max-w-screen-xl px-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mx-auto mb-10">
        @forelse ($events as $event)
        <a href="{{ route('event.show', $event->slug) }}" class="block h-full">
            <div class="event-card">
                <div class="event-card-img">
                    @if($event->poster)
                        <img src="{{ asset('poster/' . $event->poster) }}" alt="{{ $event->title }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                            <svg class="w-10 h-10 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="event-card-body">
                    <h3 class="event-title">{{ $event->title }}</h3>
                    <div class="event-meta">
                        <div class="event-meta-row">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $event->event_date }}</span>
                        </div>
                        <div class="event-meta-row">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="line-clamp-1">{{ $event->location }}</span>
                        </div>
                        @if($event->ticketTypes->isNotEmpty())
                            <div class="event-price">
                                @if($event->ticketTypes->min('price') > 0)
                                    Mulai Rp {{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}
                                @else
                                    <span class="free">Gratis</span>
                                @endif
                            </div>
                        @else
                            <div class="event-price free">Gratis</div>
                        @endif
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-4 text-center py-24 text-gray-400">
            <svg class="w-14 h-14 mx-auto mb-4 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-base font-semibold text-gray-500">Tidak ada event ditemukan.</p>
            @if(request('search') || request('category'))
                <a href="{{ route('homepage') }}" class="text-[#6B0080] hover:underline mt-2 inline-block text-sm">Lihat semua event</a>
            @endif
        </div>
        @endforelse
    </div>

    {{-- PURPLE PAGINATION --}}
    @if($events->hasPages())
    <div class="flex justify-center mb-14 px-4 pagination-purple">
        {{ $events->withQueryString()->links() }}
    </div>
    @else
    <div class="mb-14"></div>
    @endif

@endsection

@push('scripts')
<script>
function selectCategoryHp(id) {
    document.getElementById('category-input-hp').value = id;
    document.getElementById('category-dropdown-hp').classList.add('hidden');
    document.getElementById('search-form-hp').submit();
}
document.getElementById('filter-btn-hp').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('category-dropdown-hp').classList.toggle('hidden');
});
document.addEventListener('click', function(e) {
    const dd = document.getElementById('category-dropdown-hp');
    if (dd && !dd.contains(e.target) && e.target !== document.getElementById('filter-btn-hp'))
        dd.classList.add('hidden');
});
</script>
@endpush
