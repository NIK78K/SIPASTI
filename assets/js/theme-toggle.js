/* ========================================
   THEME TOGGLE SCRIPT
   Toggle antara Light Mode dan Dark Mode
   ======================================== */

// Fungsi untuk mendapatkan theme saat ini
function getCurrentTheme() {
    // Cek localStorage dulu
    const savedTheme = localStorage.getItem('sipasti-theme');
    if (savedTheme) {
        return savedTheme;
    }
    
    // Jika belum ada di localStorage, cek preferensi sistem
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
        return 'light';
    }
    
    // Default ke dark mode
    return 'dark';
}

// Fungsi untuk set theme
function setTheme(theme) {
    if (theme === 'light') {
        document.documentElement.classList.add('light');
        localStorage.setItem('sipasti-theme', 'light');
    } else {
        document.documentElement.classList.remove('light');
        localStorage.setItem('sipasti-theme', 'dark');
    }
}

// Fungsi untuk toggle theme
function toggleTheme() {
    const currentTheme = getCurrentTheme();
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
    
    // Animasi smooth saat toggle
    document.body.style.transition = 'background-color 0.3s ease';
    
    // Optional: Tampilkan notifikasi
    showThemeNotification(newTheme);
}

// Fungsi untuk menampilkan notifikasi saat ganti theme
function showThemeNotification(theme) {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 z-50 px-6 py-3 rounded-xl shadow-2xl 
                             backdrop-blur-xl border transition-all duration-500 transform translate-y-full`;
    
    if (theme === 'light') {
        notification.className += ' bg-white/90 border-gray-200 text-gray-800';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold">Light Mode Aktif</span>
            </div>
        `;
    } else {
        notification.className += ' bg-slate-800/90 border-slate-600 text-white';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
                <span class="font-semibold">Dark Mode Aktif</span>
            </div>
        `;
    }
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => notification.classList.remove('translate-y-full'), 100);
    
    // Slide out dan hapus
    setTimeout(() => {
        notification.classList.add('translate-y-full');
        setTimeout(() => notification.remove(), 500);
    }, 2000);
}

// Inisialisasi theme saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    const theme = getCurrentTheme();
    setTheme(theme);
    console.log('🎨 Theme initialized:', theme);
});

// Listen ke perubahan preferensi sistem (optional)
if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', e => {
        if (!localStorage.getItem('sipasti-theme')) {
            // Hanya auto-switch jika user belum set manual preference
            setTheme(e.matches ? 'light' : 'dark');
        }
    });
}

// Export fungsi untuk digunakan di HTML
window.toggleTheme = toggleTheme;
window.setTheme = setTheme;
window.getCurrentTheme = getCurrentTheme;