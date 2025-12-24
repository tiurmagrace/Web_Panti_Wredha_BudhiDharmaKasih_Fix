/* ========================================
   TAMBAH DONASI - FIXED
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            showError: false, 
            previewImage: null, 
            unreadCount: 0, 
            isLoading: false,
            currentUrl: window.location.href, 
            activePage: 'donasi', 
            searchQuery: '',
            form: { 
                donatur: '', 
                jenis: '', 
                detail: '', 
                jumlah: '', 
                tanggal_raw: '', 
                status: '', 
                petugas: '' 
            }
        }
    },
    mounted() {
        console.log('✅ Tambah Donasi Mounted!');
        // Set tanggal default ke hari ini
        this.form.tanggal_raw = new Date().toISOString().split('T')[0];
    },
    watch: { 
        'form.jenis'(newValue) { 
            this.form.detail = ''; 
        } 
    },
    methods: {
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.previewImage = e.target.result; };
                reader.readAsDataURL(file);
            }
        },

        async validateAndSubmit() {
            this.showError = false;
            
            // Validasi minimal
            if (!this.form.donatur || !this.form.jenis) {
                this.showError = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Lengkap',
                    text: 'Donatur dan Jenis Bantuan wajib diisi!',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Simpan Data Donasi?', 
                text: 'Pastikan data sudah benar',
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
                    
                    const dataToSend = {
                        donatur: this.form.donatur,
                        jenis: this.form.jenis,
                        detail: this.form.detail || '-',
                        jumlah: this.form.jumlah || '-',
                        tanggal: this.form.tanggal_raw || new Date().toISOString().split('T')[0],
                        status: this.form.status || 'Langsung',
                        petugas: this.form.petugas || '-',
                        status_verifikasi: 'approved'
                    };

                    console.log('Sending data:', dataToSend);

                    const response = await fetch('/api/donasi', {
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
                            title: 'Berhasil!', 
                            text: 'Data donasi berhasil disimpan.', 
                            icon: 'success', 
                            timer: 1500, 
                            showConfirmButton: false
                        }).then(() => { 
                            window.location.href = '/admin/kelola-donasi?status=success'; 
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
