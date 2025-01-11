<script setup lang="ts">
import { SimplePaginate } from '@/Types';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    name: 'SimplePaginator',
});

defineProps<{
    meta: SimplePaginate<unknown>['meta'];
    links: SimplePaginate<unknown>['links'];
}>();

const url = computed(() => (page: number) => {
    const url = new URL(window.location.href);
    url.searchParams.set('page', page.toString());
    return url.toString();
});
</script>

<template>
    <nav
        class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
        aria-label="Pagination"
    >
        <div class="flex flex-1 justify-between sm:justify-end">
            <Link
                v-if="links.prev"
                :href="url(meta.current_page - 1)"
                class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0"
                >Previous</Link
            >
            <span
                v-else
                class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300"
                >Previous</span
            >
            <Link
                v-if="links.next"
                :href="url(meta.current_page + 1)"
                class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0"
                >Next</Link
            >
            <span
                v-else
                class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300"
                >Next</span
            >
        </div>
    </nav>
</template>
