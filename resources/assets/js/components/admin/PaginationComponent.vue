<template>
    <nav role="navigation" aria-label="pagination">
        <ul class="pagination justify-content-center pagination-sm">
            <li v-if="onFirstPage()" class="page-item disabled"><span class="page-link">&laquo;</span></li>
            <li v-else class="page-item"><a class="page-link" @click.prevent="changePage(pagination.current_page - 1)" rel="prev">&laquo;</a></li>
            <li class="page-item" :class="isCurrentPage(page) ? 'active' : ''" v-for="page in pages">
                <span v-if="isCurrentPage(page)" class="page-link">{{ page }}</span>
                <a v-else class="page-link" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li v-if="hasMorePages()" class="page-item"><a class="page-link" @click.prevent="changePage(pagination.current_page + 1)" rel="next">&raquo;</a></li>
            <li v-else class="page-item disabled"><span class="page-link">&raquo;</span></li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: ['pagination', 'offset'],
        methods: {
            onFirstPage() {
                return this.pagination.current_page <= 1;
            },
            hasMorePages() {
                return this.pagination.current_page < this.pagination.last_page;
            },
            isCurrentPage(page) {
                return this.pagination.current_page === page;
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = [];
                let from = this.pagination.current_page - Math.floor(this.offset / 2);
                if (from < 1) {
                    from = 1;
                }
                let to = from + this.offset - 1;
                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                while (from <= to) {
                    pages.push(from);
                    from++;
                }
                return pages;
            }
        }
    }
</script>