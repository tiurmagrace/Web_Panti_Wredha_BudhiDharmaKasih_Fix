/* ========================================
   TAMBAH BARANG - FIXED
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

// Mount ke #tambahBarangApp yang ada di view
createApp({
    data() {
        return {
            showError: false, 
            previewImage: null, 
            hasExpired: false, 
            isLoading: false,
            searchQuery: '', 
            activePage: 'barang', 
            unreadCount: 0, 
            currentUrl: window.location.href,
            form: {
                nama: '', 
                kategori: '', 
                satuan: 'Pcs', 
                stok: '',
                kondisi: 'Baik', 
                tgl_masuk_raw: '', 
                expired_raw: ''
            }
        }
    },
    mounted() {
        console.log('✅ Tambah Barang Mounted!');
        // Set tanggal default ke hari ini
        this.form.tgl_masuk_raw = new Date().toISOString().split('T')[0];
    },
    methods: {
        handleFileUpload(e) {
            const file = e.target.files[0];
            if (file) this.previewImage = URL.createObjectURL(file);
        },
        
        async validateAndSubmit() {
            this.showError = false;
            
            // Validasi minimal - hanya nama yang wajib
            if (!this.form.nama) {
                this.showError = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Lengkap',
                    text: 'Nama barang wajib diisi!',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Simpan Data Barang?', 
                text: 'Pastikan data sudah benar',
                icon: 'question', 
                showCancelButton: true,
                confirmButtonColor: '#1a5c7a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                this.isLoading = true;
                
                try {
                    const token = localStorage.getItem('admin_token');
                    
                    // Siapkan data untuk dikirim
                    const stokAwal = parseInt(this.form.stok) || 0;
                    
                    const dataToSend = {
                        nama: this.form.nama,
                        kategori: this.form.kategori || 'Lainnya',
                        satuan: this.form.satuan || 'Pcs',
                        brg_masuk: stokAwal,
                        sisa_stok: stokAwal,
                        tgl_masuk: this.form.tgl_masuk_raw || new Date().toISOString().split('T')[0],
                        kondisi: this.form.kondisi || 'Baik'
                    };
                    
                    // Tambahkan expired jika ada
                    if (this.hasExpired && this.form.expired_raw) {
                        dataToSend.expired = this.form.expired_raw;
                    }

                    console.log('Sending data:', dataToSend);

                    const response = await fetch('/api/barang', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(dataToSend)
                    });

                    const data = await response.json();
                    
                    console.log('Response:', data);

                    if (response.ok && data.success) {
                        Swal.fire({
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: 'Data barang berhasil ditambahkan', 
                            confirmButtonColor: '#1a5c7a'
                        }).then(() => {
                            window.location.href = '/admin/data-barang';
                        });
                    } else {
                        let errorMsg = data.message || 'Gagal menyimpan data';
                        if (data.errors) {
                            errorMsg = Object.values(data.errors).flat().join('\n');
                        }
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Error', 
                            text: errorMsg 
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error Koneksi', 
                        text: 'Gagal terhubung ke server. Pastikan server Laravel berjalan.' 
                    });
                } finally {
                    this.isLoading = false;
                }
            }
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
                    localStorage.removeItem('admin_token');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
}).mount('#adminApp');
