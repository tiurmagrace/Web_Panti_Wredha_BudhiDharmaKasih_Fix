console.log('FOOTER VUE LOADED');

if (document.getElementById('footerApp')) {
    Vue.createApp({
        data() {
            return {
                searchQuery: ''
            };
        },
        methods: {
            performSearch() {
                const keyword = this.searchQuery.trim().toLowerCase();

                if (keyword.length < 3) {
                    Swal.fire('Info', 'Minimal 3 huruf', 'info');
                    return;
                }

                const mainContent =
                    document.getElementById('homepageApp') ||
                    document.querySelector('main');

                if (!mainContent) return;

                // hapus highlight lama
                document.querySelectorAll('.highlight-text').forEach(el => {
                    el.replaceWith(el.textContent);
                });

                const regex = new RegExp(`(${keyword})`, 'gi');
                let found = false;

                const walk = node => {
                    if (node.nodeType === 3 && regex.test(node.textContent)) {
                        const span = document.createElement('span');
                        span.innerHTML = node.textContent.replace(
                            regex,
                            '<span class="highlight-text">$1</span>'
                        );
                        node.replaceWith(span);
                        found = true;
                    } else if (
                        node.nodeType === 1 &&
                        !['SCRIPT', 'STYLE'].includes(node.tagName)
                    ) {
                        [...node.childNodes].forEach(walk);
                    }
                };

                walk(mainContent);

                if (found) {
                    document
                        .querySelector('.highlight-text')
                        ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    Swal.fire(
                        'Tidak Ditemukan',
                        `"${this.searchQuery}" tidak ada di halaman ini`,
                        'warning'
                    );
                }
            }
        }
    }).mount('#footerApp');
}
