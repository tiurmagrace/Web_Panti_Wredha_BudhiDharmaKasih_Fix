// ==========================================
// 1. edit-donasi.js - FIXED
// ==========================================
// File: public/assets/js/edit-donasi.js

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', form: {}, editIndex: -1, previewImage: null,
            unreadCount: 0, currentUrl: window.location.href, currentPage: 'donasi'
        }
    },
    watch: { 'form.jenis'(newValue) { this.form.detail = ''; } },
    mounted() {
        const editData = JSON.parse(localStorage.getItem('editDonasiData'));
        if (editData) {
            this.form = editData.data;
            this.editIndex = editData.index;
            if (this.form.foto) this.previewImage = this.form.foto;
            else this.previewImage = null;
        } else {
            window.location.href = '/admin/kelola-donasi';
        }
    },
    methods: {
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
        validateAndSave() {
            Swal.fire({
                title: 'Simpan Perubahan?', icon: 'question', showCancelButton: true,
                confirmButtonText: 'Ya', cancelButtonText: 'Batal',
                confirmButtonColor: '#1a5c7a', cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    try {
                        let mainData = JSON.parse(localStorage.getItem('donasiList'));
                        if(this.form.tanggal_raw) {
                            let d = new Date(this.form.tanggal_raw);
                            let day = String(d.getDate()).padStart(2, '0');
                            let month = String(d.getMonth() + 1).padStart(2, '0');
                            let year = d.getFullYear();
                            this.form.tanggal = `${day}/${month}/${year}`;
                        }
                        if(mainData && this.editIndex !== -1) {
                            mainData[this.editIndex] = this.form;
                            localStorage.setItem('donasiList', JSON.stringify(mainData));
                        }
                        let logs = JSON.parse(localStorage.getItem('activityLog')) || [];
                        let jam = new Date().toLocaleString('id-ID', { 
                            day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' 
                        });
                        logs.push({ text: `Admin mengubah data donasi dari: ${this.form.donatur}`, time: jam });
                        localStorage.setItem('activityLog', JSON.stringify(logs));
                        localStorage.removeItem('editDonasiData');
                        window.location.href = '/admin/kelola-donasi?status=edited';
                    } catch (e) {
                        Swal.fire('Error', 'Gagal menyimpan (mungkin file foto terlalu besar)', 'error');
                    }
                }
            });
        },
        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?', text: "Sesi admin akan diakhiri.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('adminLoggedIn');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
}).mount('#adminApp');





