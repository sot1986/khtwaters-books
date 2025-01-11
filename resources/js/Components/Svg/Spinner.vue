<script setup lang="ts">
defineOptions({
    name: 'SvgSpinner',
});
</script>

<template>
    <svg
        class="animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
    >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        />
        <path
            class="opacity-75"
            d="M4 12a8 8 0 018-8V2.5"
            stroke="currentColor"
            stroke-width="4"
        />
    </svg>
</template>
