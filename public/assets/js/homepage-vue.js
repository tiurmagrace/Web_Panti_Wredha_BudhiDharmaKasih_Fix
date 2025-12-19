const { createApp } = Vue;

createApp({
    data() {
        return {
            currentPage: 1,
            perPage: 6,
            activeImage: {},

            images: [
                { id: 1, src: '/assets/images/7.png', alt: 'Dokumentasi 1' },
                { id: 2, src: '/assets/images/8.png', alt: 'Dokumentasi 2' },
                { id: 3, src: '/assets/images/9.png', alt: 'Dokumentasi 3' },
                { id: 4, src: '/assets/images/7.png', alt: 'Dokumentasi 4' },
                { id: 5, src: '/assets/images/8.png', alt: 'Dokumentasi 5' },
                { id: 6, src: '/assets/images/9.png', alt: 'Dokumentasi 6' },
                { id: 7, src: '/assets/images/7.png', alt: 'Dokumentasi 7' },
                { id: 8, src: '/assets/images/8.png', alt: 'Dokumentasi 8' },
            ]
        }
    },

    computed: {
        totalPages() {
            return Math.ceil(this.images.length / this.perPage);
        },
        paginatedImages() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.images.slice(start, start + this.perPage);
        }
    },

    methods: {
        setPage(page) {
            this.currentPage = page;
            window.scrollTo({ top: 600, behavior: 'smooth' });
        },
        openModal(img) {
            this.activeImage = img;
            new bootstrap.Modal(
                document.getElementById('galleryModal')
            ).show();
        }
    }
}).mount('#homepageApp');
