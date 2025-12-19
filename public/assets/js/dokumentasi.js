const { createApp } = Vue;

if (document.getElementById('dokumentasiApp')) {
    createApp({
        data() {
            return {
                page: 1,
                perPage: 4,
                dokumentasi: [
                    // isi data kamu
                ]
            };
        },
        computed: {
            paginatedData() {
                const start = (this.page - 1) * this.perPage;
                return this.dokumentasi.slice(start, start + this.perPage);
            }
        },
        methods: {
            nextPage() {
                this.page++;
            },
            prevPage() {
                if (this.page > 1) this.page--;
            }
        }
    }).mount('#dokumentasiApp');
}
