import { router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { ref, watch } from 'vue';

export default function useSearch() {
    const search = ref(
        new URLSearchParams(window.location.search).get('search') ?? '',
    );
    const rawSearch = ref(search.value);

    watchDebounced(
        rawSearch,
        (rs) => {
            search.value = rs;
        },
        { debounce: 300 },
    );

    return {
        search,
        rawSearch,
    };
}

export function useSearchQuery() {
    const { search, rawSearch } = useSearch();

    watch(search, (s) => {
        router.reload({
            data: { search: s, page: 1 },
            replace: true,
        });
    });

    return {
        search,
        rawSearch,
    };
}
