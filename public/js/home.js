function openLoginModal() {
    const modal = document.getElementById('login-modal');
    const overlay = document.getElementById('modal-overlay');
    const content = document.getElementById('modal-content');

    // 1. Tampilkan container utama
    modal.classList.remove('invisible');

    // 2. Beri jeda sangat singkat agar browser sempat merender perubahan sebelum animasi
    setTimeout(() => {
        // Overlay jadi hitam transparan
        overlay.classList.replace('bg-black/0', 'bg-black/50');
        overlay.classList.replace('backdrop-blur-none', 'backdrop-blur-sm');

        // Content jadi ukuran normal dan muncul
        content.classList.replace('scale-95', 'scale-100');
        content.classList.replace('opacity-0', 'opacity-100');
    }, 10);

    document.getElementById('user-dropdown').classList.add('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    const modal = document.getElementById('login-modal');
    const overlay = document.getElementById('modal-overlay');
    const content = document.getElementById('modal-content');

    // 1. Kembalikan animasi ke kondisi awal
    overlay.classList.replace('bg-black/50', 'bg-black/0');
    overlay.classList.replace('backdrop-blur-sm', 'backdrop-blur-none');

    content.classList.replace('scale-100', 'scale-95');
    content.classList.replace('opacity-100', 'opacity-0');

    // 2. Sembunyikan container setelah animasi selesai (300ms sesuai duration-300)
    setTimeout(() => {
        modal.classList.add('invisible');
        document.body.style.overflow = '';
    }, 300);
}

// Update event listener klik di luar modal
document.getElementById('modal-overlay').addEventListener('click', closeLoginModal);
