// ==========================================
// 2. lupa-password-admin.js - FIXED FOR LARAVEL
// ==========================================
// File: public/assets/js/lupa-password-admin.js

const { createApp } = Vue;

createApp({
    data() {
        return {
            email: '',
            isLoading: false
        }
    },
    methods: {
        kirimLink() {
            this.isLoading = true;
            
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Link Terkirim!',
                    text: `Link reset telah dikirim ke ${this.email}`,
                    confirmButtonColor: '#1a5c7a'
                }).then(() => {
                    // FIXED: Redirect ke reset password Laravel
                    window.location.href = '/admin/reset-password'; 
                });
            }, 1000);
        }
    }
}).mount('#lupaApp');