/* ========================================
   MAIN ADMIN JS - GLOBAL VUE INSTANCE (FIXED)
   ======================================== */

// --- SECURITY CHECK (SATPAM) ---
if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

// 1. Definisikan Config Utama (Header, Sidebar, Global State)
const mainConfig = {
    data() {
        return {
            // GLOBAL STATES
            currentUrl: window.location.href,
            currentPage: '', // 'dashboard', 'penghuni', 'donasi', 'barang', 'notifikasi'
            searchQuery: '',
            unreadCount: 0
        }
    },
    mounted() {
        // Deteksi halaman aktif untuk sidebar
        this.detectCurrentPage();
        
        // Load notifikasi count
        this.loadNotificationCount();

        console.log('✅ Main Admin App Mounted');
    },
    methods: {
        detectCurrentPage() {
            const url = this.currentUrl;
            
            if (url.includes('/admin/dashboard') || url.includes('/admin/index')) {
                this.currentPage = 'dashboard';
            } else if (url.includes('penghuni')) {
                this.currentPage = 'penghuni';
            } else if (url.includes('donasi')) {
                this.currentPage = 'donasi';
            } else if (url.includes('barang') || url.includes('ambil-stok')) {
                this.currentPage = 'barang';
            } else if (url.includes('notifikasi')) {
                this.currentPage = 'notifikasi';
            }
        },
        
        loadNotificationCount() {
            const notifs = JSON.parse(localStorage.getItem('notifications')) || [];
            this.unreadCount = notifs.filter(n => !n.isRead).length;
        },
        
        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?',
                text: "Sesi admin akan diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('adminLoggedIn');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
};

// 2. CEK LOGIKA HALAMAN SPESIFIK (window.PageLogic)
// Ini bagian pentingnya: Gabungkan logika halaman (child) ke induk (main)
let appConfig = { ...mainConfig };

if (window.PageLogic) {
    console.log('🚀 Page Logic Detected & Merged!');
    // Gunakan mixins untuk menggabungkan data/methods halaman ke main app
    appConfig.mixins = [window.PageLogic];
}

// 3. MOUNT VUE APP (HANYA SEKALI DI SINI)
const adminApp = createApp(appConfig).mount('#adminApp');

// Export agar bisa diakses global jika perlu
window.adminApp = adminApp;