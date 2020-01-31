<template>
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <ul class="pagination">
            <li class="page-item">
                <a href="#" class="page-link" @click.prevent="changePage(1)" :disabled="pagination.current_page <= 1">&laquo;</a>
            </li>
            <li class="page-item">
                <a href="#" class="page-link" @click.prevent="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1">&lsaquo;</a>
            </li>
            <li v-for="(page,index) in pages" :key="index" :class="isCurrentPage(page) ? 'page-item active' : 'page-item'">
                <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li class="page-item">
                <a href="#" class="page-link" @click.prevent="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page">&rsaquo;</a>
            </li>
            <li class="page-item">
                <a href="#" class="page-link" @click.prevent="changePage(pagination.last_page)" :disabled="pagination.current_page >= pagination.last_page">&raquo;</a>
            </li>
        </ul>
    </nav>
</template>

<style>
    .pagination {
        margin-top: 40px;
    }
</style>

<script>
export default {
    name: 'Pagination',
    props: ['pagination', 'offset'],
    methods: {
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
