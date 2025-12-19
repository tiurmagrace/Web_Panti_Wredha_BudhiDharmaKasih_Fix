const { createApp } = Vue;

if (document.getElementById('footerApp')) {
    createApp({
        data() {
            return {
                searchQuery: ''
            };
        },
        methods: {
            performSearch() {
                const keyword = this.searchQuery.trim();
                if (keyword.length < 3) {
                    Swal.fire('Info', 'Minimal 3 huruf', 'info');
                    return;
                }

                const content = document.querySelector('main');
                if (!content) return;

                // hapus highlight lama
                document.querySelectorAll('.highlight-text')
                    .forEach(el => el.outerHTML = el.innerText);

                const regex = new RegExp(`(${keyword})`, 'gi');
                let found = false;

                function highlight(node) {
                    if (node.nodeType === 3 && regex.test(node.data)) {
                        const span = document.createElement('span');
                        span.innerHTML = node.data.replace(
                            regex,
                            '<span class="highlight-text">$1</span>'
                        );
                        node.parentNode.replaceChild(span, node);
                        found = true;
                    } else if (
                        node.nodeType === 1 &&
                        node.childNodes &&
                        !/(script|style)/i.test(node.tagName)
                    ) {
                        node.childNodes.forEach(child => highlight(child));
                    }
                }

                highlight(content);

                if (found) {
                    document.querySelector('.highlight-text')
                        ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    Swal.fire(
                        'Tidak Ditemukan',
                        `"${keyword}" tidak ada di halaman ini`,
                        'warning'
                    );
                }
            }
        }
    }).mount('#footerApp');
}
