import { computed, ref } from 'vue';

export default function <T extends string[]>(defaultSort: T[number]) {
    const sort = ref(defaultSort);
    const direction = ref<'asc' | 'desc'>('asc');

    const sortBy = (field: T[number]) => {
        if (sort.value === field) {
            direction.value = direction.value === 'asc' ? 'desc' : 'asc';
        } else {
            sort.value = field;
            direction.value = 'asc';
        }
    };

    const sortParams = computed(() => ({
        sort: sort.value as T[number],
        direction: direction.value,
    }));

    return {
        sort,
        direction,
        sortBy,
        sortParams,
    };
}
