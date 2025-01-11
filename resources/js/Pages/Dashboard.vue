<script setup lang="ts">
import useHeader from '@/Composables/useHeader';
import AdminLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({
    name: 'DashboardPage',
    layout: AdminLayout,
});

const { setHeaderTitle } = useHeader();
setHeaderTitle('Dashboard');
</script>

<template>
    <Head title="Dashboard" />
</template>
