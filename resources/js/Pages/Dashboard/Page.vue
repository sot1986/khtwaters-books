<script setup lang="ts">
import useAuth from '@/Composables/useAuth';
import useHeader from '@/Composables/useHeader';
import AdminLayout from '@/Layouts/AuthenticatedLayout.vue';
import { UserCircleIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    name: 'DashboardPage',
    layout: AdminLayout,
});

const { setHeaderTitle } = useHeader();
setHeaderTitle('Dashboard');

defineProps<{
    stats: { label: string; value: string; link: string }[];
}>();

const { auth } = useAuth(true);
</script>

<template>
    <Head title="Dashboard" />

    <section aria-labelledby="profile-overview-title">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <h2 class="sr-only" id="profile-overview-title">
                Profile Overview
            </h2>
            <div class="bg-white p-6">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="sm:flex sm:space-x-5">
                        <div class="shrink-0">
                            <UserCircleIcon
                                class="mx-auto size-20 rounded-full"
                            />
                        </div>
                        <div
                            class="mt-4 text-center sm:mt-0 sm:pt-1 sm:text-left"
                        >
                            <p class="text-sm font-medium text-gray-600">
                                Welcome back,
                            </p>
                            <p
                                class="text-xl font-bold text-gray-900 sm:text-2xl"
                            >
                                {{ auth.name }}
                            </p>
                            <p class="text-sm font-medium text-gray-600">
                                {{ auth.role }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-center sm:mt-0">
                        <Link
                            :href="route('profile.edit')"
                            class="flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >View profile
                        </Link>
                    </div>
                </div>
            </div>
            <div
                class="grid grid-cols-1 divide-y divide-gray-200 border-t border-gray-200 bg-gray-50 sm:grid-cols-3 sm:divide-x sm:divide-y-0"
            >
                <div
                    v-for="stat in stats"
                    :key="stat.label"
                    class="px-6 py-5 text-center text-sm font-medium"
                >
                    <Link :href="stat.link" class="group px-2 py-1">
                        <span
                            class="text-gray-900 group-hover:text-indigo-600"
                            >{{ stat.value }}</span
                        >
                        {{ ' ' }}
                        <span
                            class="text-gray-600 group-hover:text-indigo-600"
                            >{{ stat.label }}</span
                        >
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
