console.log('HOMEPAGE VUE LOADED');

const { createApp } = Vue;

createApp({
    data() {
        return {
            /* =====================
               GALLERY STATE
            ===================== */
            currentPage: 1,
            itemsPerPage: 6,
            activeImage: {},
            galleryImages: [
                { src: '/assets/images/7.png', alt: 'Dokumentasi 1' },
                { src: '/assets/images/8.png', alt: 'Dokumentasi 2' },
                { src: '/assets/images/9.png', alt: 'Dokumentasi 3' },
                { src: '/assets/images/7.png', alt: 'Dokumentasi 4' },
                { src: '/assets/images/8.png', alt: 'Dokumentasi 5' },
                { src: '/assets/images/9.png', alt: 'Dokumentasi 6' },
                { src: '/assets/images/7.png', alt: 'Dokumentasi 7' },
                { src: '/assets/images/8.png', alt: 'Dokumentasi 8' },
            ],

            /* =====================
               FOOTER SEARCH
            ===================== */
            searchQuery: ''
        }
    },

    computed: {
        totalPages() {
            return Math.ceil(this.galleryImages.length / this.itemsPerPage);
        },

        paginatedImages() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.galleryImages.slice(start, start + this.itemsPerPage);
        }
    },

    methods: {
        /* =====================
           GALLERY PAGINATION
        ===================== */
        setPage(page) {
            this.currentPage = page;
            this.scrollToGallery();
        },

        nextPage() {
            if (this.totalPages <= 1) return;

            this.currentPage =
                this.currentPage < this.totalPages
                    ? this.currentPage + 1
                    : 1;

            this.scrollToGallery();
        },

        prevPage() {
            if (this.totalPages <= 1) return;

            this.currentPage =
                this.currentPage > 1
                    ? this.currentPage - 1
                    : this.totalPages;

            this.scrollToGallery();
        },

        scrollToGallery() {
            this.$nextTick(() => {
                const section = document.querySelector('.gallery-section');
                if (section) {
                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        },

        /* =====================
           FOOTER SEARCH (FIX)
        ===================== */
        performSearch() {
            const keyword = this.searchQuery.trim().toLowerCase();

            if (keyword.length < 3) {
                Swal.fire('Info', 'Minimal 3 huruf', 'info');
                return;
            }

            let found = false;
            const content = document.querySelector('main');

            // hapus highlight lama
            document.querySelectorAll('.highlight-text').forEach(el => {
                el.outerHTML = el.innerText;
            });

            const walk = node => {
                if (node.nodeType === 3) {
                    if (node.data.toLowerCase().includes(keyword)) {
                        const span = document.createElement('span');
                        span.innerHTML = node.data.replace(
                            new RegExp(`(${keyword})`, 'gi'),
                            '<span class="highlight-text">$1</span>'
                        );
                        node.parentNode.replaceChild(span, node);
                        found = true;
                    }
                } else if (
                    node.nodeType === 1 &&
                    !['SCRIPT', 'STYLE'].includes(node.tagName)
                ) {
                    [...node.childNodes].forEach(walk);
                }
            };

            walk(content);

            if (found) {
                document
                    .querySelector('.highlight-text')
                    .scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                Swal.fire('Tidak ditemukan', `"${this.searchQuery}" tidak ada di halaman ini`, 'warning');
            }
        }
    }
}).mount('#homepageApp');
