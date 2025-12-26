/* ========================================
   EDIT DONASI - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', 
            form: {
                donatur: '',
                jenis: '',
                detail: '',
                jumlah: '',
                tanggal: '',
                status: '',
                petugas: '',
                catatan: ''
            }, 
            originalData: null, // Simpan data asli untuk referensi
            editId: null, 
            previewImage: null, 
            isLoading: false,
            isDataLoaded: false, // Flag untuk mencegah watch saat load awal
            unreadCount: 0, 
            currentUrl: window.location.href, 
            activePage: 'donasi',
            // Daftar kategori barang yang valid
            kategoriBarang: [
                'Sembako', 'Pakaian', 'Alat Kebersihan', 'Alat Kesehatan',
                'Peralatan Rumah Tangga', 'Elektronik', 'Perlengkapan Tidur',
                'Buku & Hiburan', 'Perlengkapan Medis', 'Lainnya'
            ]
        }
    },
    computed: {
        // Cek apakah detail adalah kategori barang yang valid
        isValidKategori() {
            return this.kategoriBarang.includes(this.form.detail);
        },
        // Untuk barang dengan detail custom (tidak ada di list)
        showCustomDetail() {
            return this.form.jenis === 'Barang' && this.originalData && 
                   this.originalData.detail && !this.kategoriBarang.includes(this.originalData.detail);
        }
    },
    watch: { 
        'form.jenis'(newValue, oldValue) { 
            // Hanya reset detail jika user mengubah jenis (bukan saat load awal)
            if (this.isDataLoaded && oldValue !== '' && newValue !== oldValue) {
                this.form.detail = ''; 
            }
        } 
    },
    mounted() {
        this.loadEditData();
    },
    methods: {
        loadEditData() {
            const editData = JSON.parse(localStorage.getItem('editDonasiData'));
            if (editData && editData.data) {
                // Simpan data asli untuk referensi
                this.originalData = { ...editData.data };
                this.editId = editData.id;
                
                // Set semua field dari data yang disimpan
                this.form.donatur = editData.data.donatur || '';
                this.form.jenis = editData.data.jenis || '';
                this.form.detail = editData.data.detail || '';
                this.form.jumlah = editData.data.jumlah || '';
                this.form.status = editData.data.status || '';
                this.form.petugas = editData.data.petugas || '';
                this.form.catatan = editData.data.catatan || '';
                
                // Format tanggal untuk input date
                if (editData.data.tanggal) {
                    const d = new Date(editData.data.tanggal);
                    if (!isNaN(d.getTime())) {
                        this.form.tanggal = d.toISOString().split('T')[0];
                    }
                }
                
                // Set preview image jika ada
                if (editData.data.bukti) {
                    // Jika bukti adalah path, tambahkan prefix storage
                    if (editData.data.bukti.startsWith('data:image')) {
                        this.previewImage = editData.data.bukti;
                    } else {
                        this.previewImage = '/storage/' + editData.data.bukti;
                    }
                }
                
                // Set flag bahwa data sudah loaded
                this.$nextTick(() => {
                    this.isDataLoaded = true;
                });
            } else {
                console.error('❌ No edit data found in localStorage');
                window.location.href = '/admin/kelola-donasi';
            }
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImage = e.target.result;
                    this.form.bukti = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async validateAndSave() {
            // Validasi field wajib
            if (!this.form.donatur || !this.form.jenis || !this.form.detail) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Lengkap',
                    text: 'Donatur, Jenis, dan Detail wajib diisi!'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Simpan Perubahan?', 
                icon: 'question', 
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan', 
                cancelButtonText: 'Batal',
                confirmButtonColor: '#1a5c7a', 
                cancelButtonColor: '#d33'
            });

            if (result.isConfirmed) {
                this.isLoading = true;
                try {
                    const token = localStorage.getItem('admin_token');
                    
                    // Siapkan data yang akan dikirim
                    const dataToSend = {
                        donatur: this.form.donatur,
                        jenis: this.form.jenis,
                        detail: this.form.detail,
                        jumlah: this.form.jumlah,
                        tanggal: this.form.tanggal,
                        status: this.form.status,
                        petugas: this.form.petugas,
                        catatan: this.form.catatan
                    };

                    // Tambahkan bukti hanya jika ada perubahan (base64)
                    if (this.form.bukti && this.form.bukti.startsWith('data:image')) {
                        dataToSend.bukti = this.form.bukti;
                    }

                    const response = await fetch(`/api/donasi/${this.editId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(dataToSend)
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        localStorage.removeItem('editDonasiData');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data donasi berhasil diupdate',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/admin/kelola-donasi?status=edited';
                        });
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan');
                    }
                } catch (e) {
                    console.error('Save error:', e);
                    Swal.fire('Error', e.message, 'error');
                } finally {
                    this.isLoading = false;
                }
            }
        },

        goBack() {
            localStorage.removeItem('editDonasiData');
            window.location.href = '/admin/kelola-donasi';
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
