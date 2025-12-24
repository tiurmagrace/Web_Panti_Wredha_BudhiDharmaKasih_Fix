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
            searchQuery: '', form: {}, editId: null, previewImage: null, isLoading: false,
            unreadCount: 0, currentUrl: window.location.href, activePage: 'donasi'
        }
    },
    watch: { 'form.jenis'(newValue) { this.form.detail = ''; } },
    mounted() {
        const editData = JSON.parse(localStorage.getItem('editDonasiData'));
        if (editData) {
            this.form = editData.data;
            this.editId = editData.id;
            if (this.form.foto) this.previewImage = this.form.foto;
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

        async validateAndSave() {
            const result = await Swal.fire({
                title: 'Simpan Perubahan?', icon: 'question', showCancelButton: true,
                confirmButtonText: 'Ya', cancelButtonText: 'Batal',
                confirmButtonColor: '#1a5c7a', cancelButtonColor: '#d33'
            });

            if (result.isConfirmed) {
                this.isLoading = true;
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch(`/api/donasi/${this.editId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        localStorage.removeItem('editDonasiData');
                        window.location.href = '/admin/kelola-donasi?status=edited';
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan');
                    }
                } catch (e) {
                    Swal.fire('Error', e.message, 'error');
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
