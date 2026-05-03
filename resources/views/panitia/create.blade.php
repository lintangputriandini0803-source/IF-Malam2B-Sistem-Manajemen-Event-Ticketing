@extends('layouts.panitia')
@section('title', isset($event) ? 'Edit Event' : 'Buat Event')
@section('page-title', isset($event) ? 'Edit Event' : 'Buat Event Baru')

@section('content')

<div style="max-width:760px">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#9ca3af">
        <a href="{{ route('panitia.events.index') }}" style="color:#6B0080;text-decoration:none;font-weight:600">Event</a>
        <span>/</span>
        <span>{{ isset($event) ? 'Edit' : 'Buat Baru' }}</span>
    </div>

    <form method="POST"
          action="{{ isset($event) ? route('panitia.events.update', $event->id) : route('panitia.events.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if(isset($event)) @method('PUT') @endif

        {{-- SECTION 1: INFO DASAR --}}
        <div style="background:white;border-radius:14px;padding:24px;margin-bottom:16px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                📋 Informasi Dasar
            </h2>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                    Nama Event <span style="color:#dc2626">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}"
                       required placeholder="Masukkan nama event..."
                       style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('title') ? '#dc2626' : '#e5e7eb' }};border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                @error('title')
                <p style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                    Deskripsi
                </label>
                <textarea name="description" rows="4"
                          placeholder="Jelaskan tentang event ini..."
                          style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;resize:vertical;box-sizing:border-box;font-family:inherit"
                          onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">{{ old('description', $event->description ?? '') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Kategori <span style="color:#dc2626">*</span>
                    </label>
                    <div style="position:relative">
                        <select name="category" required
                                style="width:100%;padding:11px 36px 11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;appearance:none;background:white;cursor:pointer;box-sizing:border-box">
                            <option value="">Pilih kategori...</option>
                            @foreach(['seminar'=>'📚 Seminar','workshop'=>'🛠 Workshop','konser'=>'🎵 Konser','kompetisi'=>'🏆 Kompetisi','pameran'=>'🎨 Pameran','olahraga'=>'⚽ Olahraga','hiburan'=>'🎭 Hiburan','lainnya'=>'📌 Lainnya'] as $val=>$label)
                            <option value="{{ $val }}" {{ old('category', $event->category ?? '') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#9ca3af;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Status
                    </label>
                    <div style="position:relative">
                        <select name="status"
                                style="width:100%;padding:11px 36px 11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;appearance:none;background:white;cursor:pointer;box-sizing:border-box">
                            <option value="draft" {{ old('status', $event->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $event->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="cancelled" {{ old('status', $event->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#9ca3af;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: WAKTU & LOKASI --}}
        <div style="background:white;border-radius:14px;padding:24px;margin-bottom:16px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                📍 Waktu & Lokasi
            </h2>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Tanggal Mulai <span style="color:#dc2626">*</span>
                    </label>
                    <input type="date" name="date" value="{{ old('date', isset($event->date) ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '') }}"
                           required
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Tanggal Selesai
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date', isset($event->end_date) ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                    Lokasi <span style="color:#dc2626">*</span>
                </label>
                <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}"
                       required placeholder="Contoh: Gedung Aula PoliBatam, Batam"
                       style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>

       {{-- SECTION 3: TIKET --}}
<div style="background:white;border-radius:14px;padding:24px;margin-bottom:16px">
    <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
        🎫 Tiket
    </h2>

    <div id="tiket-list">
        @php
            $tikets = old('tikets', $event->tikets ?? [
                ['nama' => '', 'price' => '', 'quota' => '']
            ]);
            if (is_string($tikets)) $tikets = json_decode($tikets, true) ?? [];
        @endphp

        @foreach($tikets as $i => $tiket)
        <div class="tiket-item" style="border:1.5px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:12px;position:relative">

            {{-- Tombol hapus --}}
            @if($loop->index > 0)
            <button type="button" onclick="hapusTiket(this)"
                    style="position:absolute;top:12px;right:12px;background:#fee2e2;border:none;border-radius:8px;padding:4px 10px;font-size:12px;color:#ef4444;cursor:pointer;font-weight:600">
                ✕ Hapus
            </button>
            @endif

            <div style="font-size:12px;font-weight:700;color:#6B0080;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em">
                Tiket {{ $loop->iteration }}
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Nama Tiket
                    </label>
                    <input type="text" name="tikets[{{ $i }}][nama]"
                           value="{{ $tiket['nama'] ?? '' }}"
                           placeholder="cth: Regular, VIP..."
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Harga (Rp)
                    </label>
                    <input type="number" name="tikets[{{ $i }}][price]"
                           value="{{ $tiket['price'] ?? 0 }}"
                           min="0" placeholder="0 = Gratis"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">
                        Kuota
                    </label>
                    <input type="number" name="tikets[{{ $i }}][quota]"
                           value="{{ $tiket['quota'] ?? '' }}"
                           min="1" placeholder="Jumlah peserta"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tombol Tambah Tiket --}}
    <button type="button" id="btn-tambah-tiket" onclick="tambahTiket()"
            style="display:flex;align-items:center;gap:8px;padding:10px 18px;border:1.5px dashed #6B0080;border-radius:10px;background:transparent;color:#6B0080;font-size:13px;font-weight:600;cursor:pointer;margin-top:4px">
        + Tambah Jenis Tiket
    </button>
    <p id="tiket-max-msg" style="display:none;font-size:11px;color:#9ca3af;margin-top:6px">
        Maksimal 3 jenis tiket.
    </p>
</div>

<script>
const MAX_TIKET = 3;

function getTiketCount() {
    return document.querySelectorAll('.tiket-item').length;
}

function updateTambahBtn() {
    const btn = document.getElementById('btn-tambah-tiket');
    const msg = document.getElementById('tiket-max-msg');
    if (getTiketCount() >= MAX_TIKET) {
        btn.style.display = 'none';
        msg.style.display = 'block';
    } else {
        btn.style.display = 'flex';
        msg.style.display = 'none';
    }
}

function tambahTiket() {
    if (getTiketCount() >= MAX_TIKET) return;

    const index = getTiketCount();
    const wrapper = document.getElementById('tiket-list');

    const div = document.createElement('div');
    div.className = 'tiket-item';
    div.style.cssText = 'border:1.5px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:12px;position:relative';

    div.innerHTML = `
        <button type="button" onclick="hapusTiket(this)"
                style="position:absolute;top:12px;right:12px;background:#fee2e2;border:none;border-radius:8px;padding:4px 10px;font-size:12px;color:#ef4444;cursor:pointer;font-weight:600">
            ✕ Hapus
        </button>
        <div style="font-size:12px;font-weight:700;color:#6B0080;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em">
            Tiket ${index + 1}
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Nama Tiket</label>
                <input type="text" name="tikets[${index}][nama]" placeholder="cth: Regular, VIP..."
                       style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Harga (Rp)</label>
                <input type="number" name="tikets[${index}][price]" value="0" min="0" placeholder="0 = Gratis"
                       style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Kuota</label>
                <input type="number" name="tikets[${index}][quota]" min="1" placeholder="Jumlah peserta"
                       style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>
    `;

    wrapper.appendChild(div);
    updateTambahBtn();
}

function hapusTiket(btn) {
    btn.closest('.tiket-item').remove();
    reindexTikets();
    updateTambahBtn();
}

function reindexTikets() {
    document.querySelectorAll('.tiket-item').forEach((item, i) => {
        item.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/tikets\[\d+\]/, `tikets[${i}]`);
        });
        const label = item.querySelector('div[style*="color:#6B0080"]');
        if (label) label.textContent = `Tiket ${i + 1}`;
    });
}

// Cek saat halaman load (untuk edit mode)
updateTambahBtn();
</script>

        {{-- SECTION 4: POSTER --}}
        <div style="background:white;border-radius:14px;padding:24px;margin-bottom:20px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                🖼 Poster Event
            </h2>

            @if(isset($event) && $event->poster)
            <div style="margin-bottom:14px">
                <img src="{{ asset('poster/'.$event->poster) }}"
                     style="height:120px;border-radius:10px;object-fit:cover;border:2px solid #e5e7eb">
                <p style="font-size:12px;color:#9ca3af;margin-top:6px">Poster saat ini. Upload baru untuk mengganti.</p>
            </div>
            @endif

            <div id="drop-zone"
                 onclick="document.getElementById('poster-input').click()"
                 style="border:2px dashed #d1d5db;border-radius:10px;padding:32px;text-align:center;cursor:pointer;transition:all .15s"
                 onmouseover="this.style.borderColor='#6B0080';this.style.background='#fdf9ff'"
                 onmouseout="this.style.borderColor='#d1d5db';this.style.background='transparent'">
                <input type="file" id="poster-input" name="poster" accept="image/*" class="hidden"
                       onchange="previewPoster(this)">
                <div id="upload-placeholder">
                    <svg style="width:32px;height:32px;color:#d1d5db;margin:0 auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p style="font-size:14px;color:#6b7280;margin-top:8px;font-weight:500">Klik untuk upload poster</p>
                    <p style="font-size:12px;color:#9ca3af;margin-top:2px">PNG, JPG, WEBP · Maks 2MB</p>
                </div>
                <img id="poster-preview" src="" style="display:none;max-height:160px;margin:0 auto;border-radius:8px">
            </div>
        </div>

        {{-- ACTIONS --}}
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <a href="{{ route('panitia.events.index') }}"
               style="padding:11px 22px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;font-weight:600;color:#374151;text-decoration:none;background:white">
                Batal
            </a>
            <button type="submit" name="action" value="draft"
                    style="padding:11px 22px;border:1.5px solid #6B0080;border-radius:10px;font-size:14px;font-weight:600;color:#6B0080;background:white;cursor:pointer">
                Simpan Draft
            </button>
            <button type="submit" name="action" value="publish"
                    style="padding:11px 22px;border:none;border-radius:10px;font-size:14px;font-weight:700;color:white;background:#6B0080;cursor:pointer">
                {{ isset($event) ? '💾 Simpan Perubahan' : '🚀 Publish Event' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewPoster(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('upload-placeholder').style.display = 'none';
            const preview = document.getElementById('poster-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
