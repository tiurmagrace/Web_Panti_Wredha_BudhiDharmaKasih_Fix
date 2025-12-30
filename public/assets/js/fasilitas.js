// Wait for DOM ready to avoid Vue conflicts
document.addEventListener('DOMContentLoaded', function() {
    const fasilitasEl = document.getElementById('fasilitasApp');
    if (!fasilitasEl) return;
    
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                isLoggedIn: false,
                currentUser: null,
                logoutModalInstance: null,
                searchQuery: ''
            }
        },
        mounted() {
            const status = localStorage.getItem('isLoggedIn');
            const userData = localStorage.getItem('user_sementara');

            if (status === 'true' && userData) {
                this.isLoggedIn = true;
                try {
                    this.currentUser = JSON.parse(userData);
                } catch (e) {
                    this.currentUser = null;
                }
            }
        },
        methods: {
            showLogoutModal() {
                const modalEl = document.getElementById('logoutModal');
                if (!modalEl) return;
                this.logoutModalInstance = new bootstrap.Modal(modalEl);
                this.logoutModalInstance.show();
            },
            confirmLogout() {
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('redirect_after_login');
                window.location.href = '/'; 
            },
            
            performSearch() {
                if (!this.searchQuery) return;

                document.querySelectorAll('.highlight-text').forEach(el => {
                    el.outerHTML = el.innerText;
                });

                const term = this.searchQuery.trim();
                if (term.length < 3) {
                    Swal.fire('Info', 'Kata kunci minimal 3 huruf', 'info');
                    return;
                }

                const content = document.querySelector('main'); 
                const regex = new RegExp(`(${term})`, 'gi');
                let found = false;

                function highlightText(node) {
                    if (node.nodeType === 3) {
                        const match = node.data.match(regex);
                        if (match) {
                            const span = document.createElement('span');
                            span.innerHTML = node.data.replace(regex, '<span class="highlight-text">$1</span>');
                            node.parentNode.replaceChild(span, node);
                            found = true;
                        }
                    } else if (node.nodeType === 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
                        for (let i = 0; i < node.childNodes.length; i++) {
                            highlightText(node.childNodes[i]);
                        }
                    }
                }

                highlightText(content);

                if (found) {
                    const firstHighlight = document.querySelector('.highlight-text');
                    if (firstHighlight) {
                        firstHighlight.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    Swal.fire('Tidak Ditemukan', `Kata "${term}" tidak ada di halaman ini.`, 'warning');
                }
            }
        }
    }).mount('#fasilitasApp');
});