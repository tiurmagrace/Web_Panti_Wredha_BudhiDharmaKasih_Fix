/* ========================================
   TAMBAH PENGHUNI - FIXED
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            step: 1,
            previewImage: null,
            unreadCount: 0,
            activePage: 'penghuni',
            searchQuery: '',
            currentUrl: window.location.href,
            isLoading: false,
            form: {
                nama: '', 
                nik: '', 
                ttl: '', 
                usia: '', 
                gender: '', 
                agama: '', 
                status: '', 
                alamat: '', 
                kota: '', 
                pj: '', 
                hubungan: '', 
                telp: '', 
                alamat_pj: '', 
                penyakit: '', 
                kebutuhan: '', 
                alergi: '', 
                obat: '', 
                status_sehat: '', 
                tgl_masuk: '', 
                rujukan: '', 
                catatan: '', 
                paviliun: '', 
                foto: null
            }
        }
    },
    
    mounted() {
        console.log('✅ Vue Tambah Penghuni Mounted!');
    },
    
    methods: {
        nextStep() { 
            this.step++;
        },
        
        filterAngka(field) {
            this.form[field] = this.form[field].replace(/[^0-9]/g, '');
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImage = e.target.result;
                    this.form.foto = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async validateAndSubmit() {
            // Validasi minimal - hanya nama yang wajib
            if (!this.form.nama || this.form.nama.trim() === '') {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Data Tidak Lengkap', 
                    text: 'Nama penghuni wajib diisi!', 
                    confirmButtonColor: '#d33' 
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Simpan Data Penghuni?',
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
                    
                    // Siapkan data untuk dikirim - bersihkan field kosong
                    const dataToSend = {};
                    
                    for (const [key, value] of Object.entries(this.form)) {
                        // Skip field yang kosong atau null, kecuali nama
                        if (value !== null && value !== '' && value !== undefined) {
                            if (key === 'usia') {
                                // Konversi usia ke integer
                                dataToSend[key] = parseInt(value) || null;
                            } else {
                                dataToSend[key] = value;
                            }
                        }
                    }

                    const response = await fetch('/api/penghuni', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(dataToSend)
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: 'Data penghuni berhasil ditambahkan', 
                            timer: 1500, 
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/admin/kelola-penghuni?status=success';
                        });
                    } else {
                        // Tampilkan error dari server dengan format yang lebih baik
                        let errorMsg = data.message || 'Gagal menyimpan data';
                        if (data.errors) {
                            const errorList = Object.values(data.errors).flat();
                            errorMsg = errorList.join(', ');
                        }
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Gagal Menyimpan', 
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
