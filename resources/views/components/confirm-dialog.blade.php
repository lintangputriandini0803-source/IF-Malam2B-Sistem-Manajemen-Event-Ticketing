<div id="simetix-confirm-overlay"
     style="display:none;position:fixed;inset:0;z-index:10000;
            background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);
            align-items:center;justify-content:center;padding:16px">

    <div id="simetix-confirm-box"
         style="background:white;border-radius:20px;width:100%;max-width:420px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);
                animation:confirmIn .25s cubic-bezier(.21,1.02,.73,1) forwards">

        {{-- Header --}}
        <div id="simetix-confirm-header" style="padding:24px 24px 0;text-align:center">
            <div id="simetix-confirm-icon"
                 style="width:52px;height:52px;border-radius:50%;margin:0 auto 14px;
                        display:flex;align-items:center;justify-content:center">
            </div>
            <h3 id="simetix-confirm-title"
                style="font-size:16px;font-weight:800;color:#111;margin:0 0 8px"></h3>
            <p id="simetix-confirm-message"
               style="font-size:14px;color:#6b7280;line-height:1.55;margin:0"></p>
        </div>

        {{-- Detail box (opsional) --}}
        <div id="simetix-confirm-detail-wrap" style="display:none;padding:12px 24px 0">
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;
                        padding:10px 14px;display:flex;align-items:center;gap:10px">
                <svg style="width:16px;height:16px;color:#2563eb;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="simetix-confirm-detail" style="font-size:13px;color:#1d4ed8;font-weight:600"></span>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="padding:20px 24px 24px;display:flex;gap:10px">
            <button id="simetix-confirm-cancel"
                    onclick="SimetixConfirm.close()"
                    style="flex:1;padding:11px;border-radius:10px;border:1.5px solid #e5e7eb;
                           background:white;font-size:14px;font-weight:600;color:#374151;cursor:pointer">
                Batal
            </button>
            <button id="simetix-confirm-ok"
                    style="flex:2;padding:11px;border-radius:10px;border:none;
                           font-size:14px;font-weight:700;color:white;cursor:pointer">
                Konfirmasi
            </button>
        </div>
    </div>
</div>

<style>
@keyframes confirmIn {
    from { opacity:0; transform:scale(.94) translateY(12px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
</style>

<script>
const SimetixConfirm = {
    _cb: null,

    show(opts) {
        const o = Object.assign({
            title: 'Konfirmasi', message: '', detail: null,
            confirm: 'Ya, Lanjutkan', cancel: 'Batal', type: 'info',
            onConfirm: null
        }, opts);

        const types = {
            info:    { bg:'#dbeafe', color:'#2563eb', icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>', btn:'#2563eb' },
            warning: { bg:'#fef3c7', color:'#d97706', icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>', btn:'#d97706' },
            danger:  { bg:'#fee2e2', color:'#dc2626', icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>', btn:'#dc2626' },
        };
        const t = types[o.type] || types.info;

        document.getElementById('simetix-confirm-icon').style.background = t.bg;
        document.getElementById('simetix-confirm-icon').innerHTML =
            `<svg style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="${t.color}">${t.icon}</svg>`;
        document.getElementById('simetix-confirm-title').textContent   = o.title;
        document.getElementById('simetix-confirm-message').textContent = o.message;
        document.getElementById('simetix-confirm-cancel').textContent  = o.cancel;
        document.getElementById('simetix-confirm-ok').textContent      = o.confirm;
        document.getElementById('simetix-confirm-ok').style.background = o.type === 'danger' ? '#dc2626' : '#6B0080';

        const detailWrap = document.getElementById('simetix-confirm-detail-wrap');
        if (o.detail) {
            document.getElementById('simetix-confirm-detail').textContent = o.detail;
            detailWrap.style.display = 'block';
        } else {
            detailWrap.style.display = 'none';
        }

        this._cb = o.onConfirm;
        this._confirming = false;
        const overlay = document.getElementById('simetix-confirm-overlay');
        overlay.style.display = 'flex';
        document.getElementById('simetix-confirm-box').style.animation = 'confirmIn .25s cubic-bezier(.21,1.02,.73,1) forwards';
    },

    confirm() {
        // Bug fix (High): cegah onConfirm terpanggil dua kali kalau tombol
        // konfirmasi diklik berkali-kali dengan cepat sebelum dialog tertutup.
        if (this._confirming) return;
        this._confirming = true;

        this.close();
        if (typeof this._cb === 'function') this._cb();

        // Reset guard setiap kali dialog baru dibuka (lihat show()).
    },

    close() {
        document.getElementById('simetix-confirm-overlay').style.display = 'none';
    }
};

document.getElementById('simetix-confirm-ok').addEventListener('click', () => SimetixConfirm.confirm());
document.getElementById('simetix-confirm-overlay').addEventListener('click', function(e) {
    if (e.target === this) SimetixConfirm.close();
});
</script>
