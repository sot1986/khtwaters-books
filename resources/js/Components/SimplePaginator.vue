<script setup lang="ts">
import { SimplePaginate } from '@/Types';
import { Link } from '@inertiajs/vue3';
import { computed, nextTick } from 'vue';

defineOptions({
    name: 'SimplePaginator',
});

const props = defineProps<{
    meta: SimplePaginate<unknown>['meta'];
    links: SimplePaginate<unknown>['links'];
    scrollTo?: string;
}>();

const url = computed(() => (page: number) => {
    const url = new URL(window.location.href);
    url.searchParams.set('page', page.toString());
    return url.toString();
});

function evaluateScroll() {
    if (props.scrollTo) {
        nextTick(() => {
            const element = document.getElementById(props.scrollTo!);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
}
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
                prefetch
                class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0"
                :preserve-scroll="!!scrollTo"
                v-on:success="evaluateScroll"
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
                prefetch
                class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0"
                :preserve-scroll="!!scrollTo"
                v-on:success="evaluateScroll"
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
