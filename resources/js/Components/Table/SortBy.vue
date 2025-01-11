<script setup lang="ts">
import { sortByKey } from '@/InjectionKeys';
import { ArrowDownIcon, ArrowsUpDownIcon } from '@heroicons/vue/24/outline';
import { computed, inject } from 'vue';

defineOptions({
    name: 'TableSortBy',
});

defineProps<{
    sortBy: string;
}>();

const { sortParams } = inject(
    sortByKey,
    () => ({ sortParams: computed(() => ({ sort: '', direction: 'asc' })) }),
    true,
);
</script>

<template>
    <button type="button" class="group flex items-center gap-2">
        <slot />
        <span
            class="h-4 w-4 group-hover:opacity-100"
            :class="sortParams.sort !== sortBy ? 'opacity-0' : undefined"
        >
            <ArrowsUpDownIcon v-if="sortParams.sort !== sortBy" />
            <ArrowDownIcon
                v-else
                :class="
                    sortParams.direction === 'asc' ? 'rotate-180' : undefined
                "
            />
        </span>
    </button>
</template>
