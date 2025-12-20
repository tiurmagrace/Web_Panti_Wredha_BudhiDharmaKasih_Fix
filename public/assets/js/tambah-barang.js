// ==========================================
// tambah-barang.js - FIXED activePage
// ==========================================

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            showError: false, 
            previewImage: null, 
            hasExpired: false,
            searchQuery: '', 
            activePage: 'barang', // ✅ FIXED: ganti dari currentPage
            unreadCount: 0, 
            currentUrl: window.location.href,
            form: {
                kode: '', 
                nama: '', 
                kategori: '', 
                satuan: '', 
                stok_awal: '',
                kondisi: 'Baik', 
                tgl_masuk_raw: '', 
                expired_raw: ''
            }
        }
    },
    watch: {
        'form.kategori'() { this.generateKode(); }
    },
    mounted() {
        console.log('✅ Tambah Barang Mounted!');
    },
    methods: {
        generateKode() {
            const map = { 
                'Sembako': 'SMB', 
                'Obat-obatan': 'OBT', 
                'Perlengkapan': 'PRL', 
                'Alat Kesehatan': 'ALT' 
            };
            const prefix = map[this.form.kategori] || 'BRG';
            const rand = Math.floor(100 + Math.random() * 900);
            this.form.kode = `${prefix}-${rand}`;
        },
        
        handleFileUpload(e) {
            const file = e.target.files[0];
            if (file) this.previewImage = URL.createObjectURL(file);
        },
        
        validateAndSubmit() {
            if (!this.form.nama || !this.form.kategori || !this.form.satuan || 
                !this.form.stok_awal || !this.form.tgl_masuk_raw) {
                this.showError = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Form Belum Lengkap!',
                    text: 'Mohon lengkapi semua field yang wajib diisi',
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            if (this.hasExpired && !this.form.expired_raw) {
                Swal.fire('Ups!', 'Tanggal expired wajib diisi', 'warning');
                return;
            }
            
            const stokAwal = Number(this.form.stok_awal);
            if (isNaN(stokAwal) || stokAwal <= 0) {
                Swal.fire('Error', 'Stok harus berupa angka > 0', 'error');
                return;
            }
            
            const d = new Date(this.form.tgl_masuk_raw);
            const tglMasuk = `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
            
            let expired = null;
            if (this.hasExpired) {
                const e = new Date(this.form.expired_raw);
                expired = `${String(e.getDate()).padStart(2,'0')}/${String(e.getMonth()+1).padStart(2,'0')}/${e.getFullYear()}`;
            }
            
            const newItem = {
                kode: this.form.kode, 
                nama: this.form.nama, 
                kategori: this.form.kategori,
                satuan: this.form.satuan, 
                kondisi: this.form.kondisi,
                stok_awal: stokAwal, 
                stok_keluar: 0, 
                sisa_stok: stokAwal,
                brg_masuk: `${stokAwal} ${this.form.satuan}`,
                tgl_masuk: tglMasuk, 
                expired: expired || '-', 
                tgl_keluar: '-', 
                brg_keluar: '-'
            };
            
            Swal.fire({
                title: 'Simpan Data Barang?', 
                icon: 'question', 
                showCancelButton: true,
                confirmButtonColor: '#1a5c7a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then(res => {
                if (!res.isConfirmed) return;
                
                const list = JSON.parse(localStorage.getItem('barangList')) || [];
                list.push(newItem);
                localStorage.setItem('barangList', JSON.stringify(list));
                
                // Save activity log
                const logs = JSON.parse(localStorage.getItem('activityLog')) || [];
                let jam = new Date().toLocaleString('id-ID', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                logs.push({
                    text: `Admin menambahkan barang: ${newItem.nama} (${stokAwal} ${newItem.satuan})`,
                    time: jam
                });
                localStorage.setItem('activityLog', JSON.stringify(logs));
                
                console.log('💾 Barang saved!');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data barang berhasil ditambahkan',
                    confirmButtonColor: '#1a5c7a'
                }).then(() => {
                    window.location.href = '/admin/data-barang';
                });
            });
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
}).mount('#adminApp');