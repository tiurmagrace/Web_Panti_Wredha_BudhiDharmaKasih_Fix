/* ========================================
   HOMEPAGE VUE - GALLERY PAGINATION
   ======================================== */
console.log('HOMEPAGE VUE LOADED');

// Wait for DOM ready to avoid Vue conflicts
document.addEventListener('DOMContentLoaded', function() {
    const homepageEl = document.getElementById('homepageApp');
    if (!homepageEl) return;
    
    // Check if already mounted by another script
    if (homepageEl.__vue_app__) return;
    
    Vue.createApp({
        data() {
            return {
                currentPage: 1,
                itemsPerPage: 6,
                galleryImages: [
                    { src: '/assets/images/7.png', alt: 'Dokumentasi 1' },
                    { src: '/assets/images/8.png', alt: 'Dokumentasi 2' },
                    { src: '/assets/images/9.png', alt: 'Dokumentasi 3' },
                    { src: '/assets/images/7.png', alt: 'Dokumentasi 4' },
                    { src: '/assets/images/8.png', alt: 'Dokumentasi 5' },
                    { src: '/assets/images/9.png', alt: 'Dokumentasi 6' },
                    { src: '/assets/images/7.png', alt: 'Dokumentasi 7' },
                    { src: '/assets/images/8.png', alt: 'Dokumentasi 8' },
                ]
            };
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
                    document
                        .querySelector('.gallery-section')
                        ?.scrollIntoView({ behavior: 'smooth' });
                });
            }
        }
    }).mount('#homepageApp');
});
