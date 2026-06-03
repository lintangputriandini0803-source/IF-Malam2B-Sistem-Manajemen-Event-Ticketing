@extends('layouts.public')

@section('title', 'About Us - SIMETIX')

@section('content')
<style>
    
</style>
    <div class="fixed inset-0 -z-10">
        <img src="{{asset('img/team-photo.png')}}"
             class="w-full h-full object-cover blur-sm scale-110 opacity-80">
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/35 to-black/55"></div>
</div>

    {{-- PAGE HEADER --}}
    <div class="bg-gradient-to-r from-[#4a005a] to-[#6B0080] py-8 px-5 mb-10">
        <div class="max-w-screen-xl mx-auto text-center">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $data['nama_project'] }}</h1>
            <p class="text-white/65 text-sm max-w-xl mx-auto leading-relaxed">{{ $data['deskripsi'] }}</p>
        </div>
    </div>

    <div class="max-w-screen-lg mx-auto px-6 pb-16">

        {{-- LAYANAN --}}
        <div class="mb-12">
            <div class="flex justify-center mb-8">
                <span class="section-label">Jenis Layanan</span>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($data['layanan'] as $item)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-50
                            hover:-translate-y-1 hover:shadow-md transition-all duration-200">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-[#6B0080]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-[#3a003f] mb-2">{{ $item['jenis'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $item['fitur'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- TIM PENGEMBANG --}}
        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-purple-50 mb-10">
            <div class="flex justify-center mb-8">
                <span class="section-label">Tim Pengembang</span>
            </div>

            {{-- Team Photo --}}
            @if(file_exists(public_path('img/team-photo.png')))
            <div class="flex justify-center mb-8">
                <img src="{{ asset('img/team-photo.png') }}"
                     alt="Tim SIMETIX"
                     class="rounded-2xl shadow-md max-h-64 object-cover">
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($data['tim'] as $person)
                <div class="text-center group">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl
                                flex items-center justify-center mx-auto mb-3 shadow-sm
                                group-hover:shadow-md group-hover:-translate-y-0.5 transition-all duration-200">
                        <span class="text-[#6B0080] font-bold text-xl">{{ substr($person['nama'], 0, 1) }}</span>
                    </div>
                    <h4 class="font-bold text-[#3a003f] text-sm leading-tight">{{ $person['nama'] }}</h4>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $person['nim'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- LOKASI --}}
        <div class="text-center">
            <span class="inline-flex items-center gap-2 text-sm text-gray-400">
                <svg class="w-4 h-4 text-[#9B30AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Dikembangkan di: <strong class="text-gray-600">{{ $data['lokasi'] }}</strong>
            </span>
        </div>

    </div>

@endsection
