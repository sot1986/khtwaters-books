<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SimplePaginator from '@/Components/SimplePaginator.vue';
import Spinner from '@/Components/Svg/Spinner.vue';
import SortBy from '@/Components/Table/SortBy.vue';
import TextInput from '@/Components/TextInput.vue';
import { useSearchQuery } from '@/Composables/useSearch';
import useSortTable from '@/Composables/useSortTable';
import { sortByKey } from '@/InjectionKeys';
import { AdminHandleUser, AuthUser } from '@/Models';
import { SimplePaginate } from '@/Types';
import { router } from '@inertiajs/vue3';
import { provide, ref, watch } from 'vue';

defineOptions({
    name: 'AdminUsersList',
    inheritAttrs: false,
});

defineProps<{
    users: SimplePaginate<AuthUser>;
}>();

defineEmits<{
    (e: 'edit', user: AdminHandleUser): void;
}>();

const { rawSearch, search } = useSearchQuery();

const showLoading = ref(false);

const roleCssMap = new Map<string, string>([
    ['user', 'text-gray-800 border-gray-500 bg-gray-50'],
    ['admin', 'text-blue-800 border-blue-500 bg-blue-50'],
]);

const { sortBy, sortParams } = useSortTable<['id', 'name', 'email']>('name');
provide(sortByKey, { sortParams });

watch(
    () => ({
        search: search.value,
        ...sortParams.value,
    }),
    (q) => {
        const params: Record<string, string> = {};
        if (q.search) {
            params.search = q.search;
        }
        params.sortColumn = q.sort;
        params.sortDirection = q.direction;
        router.visit(route('admin.all-users', params), {
            only: ['users'],
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                showLoading.value = true;
            },
            onFinish: () => {
                showLoading.value = false;
            },
        });
    },
);
</script>

<template>
    <div class="bg-white px-4 py-6 sm:rounded-lg sm:px-6 sm:shadow lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Users</h1>
                <p class="mt-2 text-sm text-gray-700">
                    A list of all the users in your account including their
                    name, email and role.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <PrimaryButton
                    type="button"
                    @click="
                        $emit('edit', {
                            id: 0,
                            name: '',
                            email: '',
                            role: 'user',
                        })
                    "
                    >Add user</PrimaryButton
                >
            </div>
        </div>

        <div class="mt-8 max-w-sm sm:max-w-md">
            <label for="search">Search</label>
            <TextInput
                id="search"
                v-model="rawSearch"
                class="mt-1 w-full"
                placeholder="Search users by name or email"
            />
        </div>

        <div class="mt-8 flow-root">
            <div
                class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                scroll-region
            >
                <div
                    class="relative inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                >
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                >
                                    <SortBy sort-by="id" @click="sortBy('id')">
                                        <span class="whitespace-nowrap">
                                            Id #
                                        </span>
                                    </SortBy>
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    <SortBy
                                        sort-by="name"
                                        @click="sortBy('name')"
                                    >
                                        Name
                                    </SortBy>
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    <SortBy
                                        sort-by="email"
                                        @click="sortBy('email')"
                                    >
                                        Email
                                    </SortBy>
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    Role
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-0"
                                >
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.email">
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-500 sm:pl-0"
                                >
                                    {{ user.id }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900"
                                >
                                    {{ user.name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                >
                                    {{ user.email }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span
                                        :class="roleCssMap.get(user.role)"
                                        class="mx-auto rounded-full border px-2 py-1"
                                        >{{ user.role }}</span
                                    >
                                </td>
                                <td
                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0"
                                >
                                    <button
                                        type="button"
                                        class="text-indigo-600 hover:text-indigo-900"
                                        @click="$emit('edit', user)"
                                    >
                                        Edit<span class="sr-only"
                                            >, {{ user.name }}</span
                                        >
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div
                        v-if="showLoading"
                        class="absolute inset-0 bg-white opacity-50"
                    />
                    <div
                        v-if="showLoading"
                        class="absolute inset-0 flex items-center justify-center"
                    >
                        <Spinner class="h-16 w-16" />
                    </div>
                </div>
            </div>
        </div>

        <SimplePaginator v-bind="users" class="mt-6" />
    </div>
</template>
