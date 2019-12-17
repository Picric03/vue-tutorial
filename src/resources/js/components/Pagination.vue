<template>
    <div class="pagination">
        <RouterLink v-if="!isFirstPage" :to="`/?page=${currentPage - 1}`" class="button">&laquo; prev</RouterLink>
        <RouterLink v-if="!isLastPage" :to="`/?page=${currentPage + 1}`" class="button">next &laquo;</RouterLink>
    </div>
</template>

<script>
    export default {
        props: {
            currentPage: {
                type: Number,
                require: true,
            },
            lastPage: {
                type: Number,
                require: true,
            },
        },
        computed: {
            isFirstPage() {
                return this.currentPage === 1;
            },
            isLastPage() {
                return this.currentPage === this.lastPage;
            }
        }
    }
</script>
