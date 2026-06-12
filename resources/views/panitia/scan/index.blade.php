@extends('layouts.panitia')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">

        <h1 class="text-2xl font-bold text-purple-800 mb-6">Scan Tiket</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Kolom Kiri: Form Scan --}}
            <div class="space-y-5">

                {{-- Pilih Event --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Event</label>
                    <select id="eventSelect" onchange="loadScans()" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">-- Pilih event Anda --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Area Kamera --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <p class="text-sm font-medium text-gray-700 mb-3">Scan via Kamera</p>
                    <div class="relative">
                        <video id="preview" class="w-full rounded-lg bg-black" autoplay playsinline></video>
                        <canvas id="canvas" class="hidden"></canvas>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button id="btnStart" onclick="startCamera()"
                            class="flex-1 bg-purple-700 text-white py-2 rounded-lg text-sm font-medium hover:bg-purple-800">
                            Mulai Kamera
                        </button>
                        <button id="btnStop" onclick="stopCamera()"
                            class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 hidden">
                            Stop Kamera
                        </button>
                    </div>
                </div>

                {{-- Input Manual --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <p class="text-sm font-medium text-gray-700 mb-3">Atau Input Manual</p>
                    <div class="flex gap-2">
                        <input type="text" id="manualCode" placeholder="EVT-2026-000001-01"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500 uppercase">
                        <button onclick="submitCode(document.getElementById('manualCode').value)"
                            class="bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-800">
                            Cek
                        </button>
                    </div>
                </div>

                {{-- Hasil Alert --}}
                <div id="result" class="hidden rounded-2xl p-5 text-center font-semibold text-lg"></div>

            </div>

            {{-- Kolom Kanan: Tabel Riwayat Scan --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800">Riwayat Scan</h2>
                    <span id="scanCount" class="text-xs bg-purple-100 text-purple-700 font-semibold px-3 py-1 rounded-full">0 tiket</span>
                </div>

                <div class="overflow-auto max-h-[520px]">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-white">
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 px-2 text-gray-500 font-medium">#</th>
                                <th class="text-left py-2 px-2 text-gray-500 font-medium">Kode Tiket</th>
                                <th class="text-left py-2 px-2 text-gray-500 font-medium">Nama</th>
                                <th class="text-left py-2 px-2 text-gray-500 font-medium">Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody id="scanTableBody">
                            <tr id="emptyRow">
                                <td colspan="4" class="text-center text-gray-400 py-8">Pilih event untuk melihat data scan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    let stream = null;
    let scanning = false;
    let scanList = []; // simpan data scan secara lokal

    // Load scan data dari server saat event dipilih
    function loadScans() {
        const eventId = document.getElementById('eventSelect').value;
        if (!eventId) {
            scanList = [];
            renderTable();
            return;
        }

        fetch(`{{ url('panitia/scan/list') }}?event_id=${eventId}`, {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            scanList = data.scans || [];
            renderTable();
        });
    }

    function renderTable() {
        const tbody = document.getElementById('scanTableBody');
        const count = document.getElementById('scanCount');
        count.textContent = scanList.length + ' tiket';

        if (scanList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400 py-8">Belum ada tiket yang di-scan</td></tr>`;
            return;
        }

        tbody.innerHTML = scanList.map((item, i) => `
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-2 px-2 text-gray-400">${i + 1}</td>
                <td class="py-2 px-2 font-mono text-xs text-purple-700">${item.ticket_code}</td>
                <td class="py-2 px-2 text-gray-700">${item.name}</td>
                <td class="py-2 px-2 text-gray-500 text-xs">${item.scanned_at}</td>
            </tr>
        `).join('');
    }

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(s => {
                stream = s;
                scanning = true;
                document.getElementById('preview').srcObject = s;
                document.getElementById('btnStart').classList.add('hidden');
                document.getElementById('btnStop').classList.remove('hidden');
                requestAnimationFrame(scanFrame);
            })
            .catch(() => showResult('error', 'Kamera tidak dapat diakses.'));
    }

    function stopCamera() {
        scanning = false;
        if (stream) stream.getTracks().forEach(t => t.stop());
        document.getElementById('preview').srcObject = null;
        document.getElementById('btnStart').classList.remove('hidden');
        document.getElementById('btnStop').classList.add('hidden');
    }

    function scanFrame() {
        if (!scanning) return;
        const video = document.getElementById('preview');
        const canvas = document.getElementById('canvas');
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            if (code) {
                scanning = false;
                stopCamera();
                submitCode(code.data);
                return;
            }
        }
        requestAnimationFrame(scanFrame);
    }

    function submitCode(code) {
        const eventId = document.getElementById('eventSelect').value;
        if (!eventId) { showResult('error', 'Pilih event terlebih dahulu.'); return; }
        if (!code.trim()) { showResult('error', 'Kode tiket tidak boleh kosong.'); return; }

        fetch("{{ route('panitia.scan.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ticket_code: code.trim(), event_id: eventId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                showResult('success', '✅ ' + data.message);
                // Tambah ke tabel langsung tanpa reload
                scanList.unshift({
                    ticket_code: data.ticket_code,
                    name: data.name,
                    scanned_at: data.scanned_at
                });
                renderTable();
                document.getElementById('manualCode').value = '';
            } else if (data.status === 'duplicate') {
                showResult('warning', '⚠️ Tiket sudah terdaftar! ' + data.message);
            } else {
                showResult('error', '❌ ' + data.message);
            }
        })
        .catch(() => showResult('error', '❌ Terjadi kesalahan jaringan.'));
    }

    function showResult(type, msg) {
        const el = document.getElementById('result');
        el.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800', 'bg-yellow-100', 'text-yellow-800');
        if (type === 'success') el.classList.add('bg-green-100', 'text-green-800');
        else if (type === 'warning') el.classList.add('bg-yellow-100', 'text-yellow-800');
        else el.classList.add('bg-red-100', 'text-red-800');
        el.textContent = msg;
        el.scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endpush

@endsection
