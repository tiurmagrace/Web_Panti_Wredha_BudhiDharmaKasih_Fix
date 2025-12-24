/* ========================================
   AMBIL STOK - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            selectedNamaBarang: '', selectedBarang: null,
            barangList: [], searchQuery: '', activePage: 'barang', 
            unreadCount: 0, currentUrl: window.location.href, isLoading: false,
            form: { jumlah: '', tanggal: '', keperluan: '', petugas: '' }
        }
    },
    computed: {
        availableItems() {
            return this.barangList.filter(item => item.sisa_stok > 0);
        },
        stokTersediaDisplay() {
            if (this.selectedBarang) {
                return `${this.selectedBarang.sisa_stok} ${this.selectedBarang.satuan || ''}`;
            }
            return null;
        },
        satuanBarang() {
            return this.selectedBarang ? this.selectedBarang.satuan : '';
        }
    },
    mounted() {
        this.loadBarang();
    },
    methods: {
        async loadBarang() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('/api/barang', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.barangList = data.data;
                }
            } catch (error) {
                console.error('Error loading barang:', error);
            }
        },

        cekStok() {
            // Cari barang berdasarkan nama yang diinput
            this.selectedBarang = this.barangList.find(i => 
                i.nama && i.nama.toLowerCase() === this.selectedNamaBarang.toLowerCase()
            );
        },

        async submitAmbil() {
            // Validasi: pastikan barang sudah dipilih dengan benar
            if (!this.selectedBarang) {
                Swal.fire('Error', 'Pilih barang yang valid dari daftar!', 'error'); 
                return;
            }
            if (!this.form.jumlah || !this.form.tanggal) {
                Swal.fire('Error', 'Lengkapi jumlah dan tanggal!', 'error'); 
                return;
            }

            if (parseInt(this.form.jumlah) > this.selectedBarang.sisa_stok) {
                Swal.fire('Stok Kurang!', `Hanya tersedia ${this.selectedBarang.sisa_stok} ${this.selectedBarang.satuan}`, 'error'); 
                return;
            }

            const result = await Swal.fire({
                title: 'Keluarkan Barang?', icon: 'warning', showCancelButton: true,
                confirmButtonText: 'Ya', confirmButtonColor: '#21698a'
            });

            if (result.isConfirmed) {
                this.isLoading = true;
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch('/api/barang/ambil-stok', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({
                            barang_id: this.selectedBarang.id,
                            jumlah: parseInt(this.form.jumlah),
                            tanggal: this.form.tanggal,
                            keperluan: this.form.keperluan || '-',
                            petugas: this.form.petugas || '-'
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        Swal.fire('Berhasil!', 'Barang berhasil dikeluarkan.', 'success').then(() => {
                            window.location.href = '/admin/data-barang';
                        });
                    } else {
                        throw new Error(data.message || 'Gagal mengambil stok');
                    }
                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                } finally {
                    this.isLoading = false;
                }
            }
        },

        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?', text: "Sesi admin akan diakhiri.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('adminLoggedIn');
                    localStorage.removeItem('admin_token');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
}).mount('#adminApp');
