<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SimplePaginator from '@/Components/SimplePaginator.vue';
import Spinner from '@/Components/Svg/Spinner.vue';
import SortBy from '@/Components/Table/SortBy.vue';
import TextInput from '@/Components/TextInput.vue';
import { useSearchQuery } from '@/Composables/useSearch';
import useSortTable from '@/Composables/useSortTable';
import { sortByKey } from '@/InjectionKeys';
import { AdminHandleBook, Book } from '@/Models';
import { SimplePaginate } from '@/Types';
import { toPrice } from '@/Utils';
import { router } from '@inertiajs/vue3';
import { provide, ref, watch } from 'vue';

defineOptions({
    name: 'AdminBooksList',
    inheritAttrs: false,
});

defineProps<{
    books: SimplePaginate<Book>;
}>();

defineEmits<{
    (e: 'edit', book: AdminHandleBook): void;
}>();

const { rawSearch, search } = useSearchQuery();

const showLoading = ref(false);

const { sortBy, sortParams } =
    useSortTable<['id', 'title', 'price', 'likes']>('title');
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
        router.visit(route('admin.all-books', params), {
            only: ['books'],
            preserveScroll: true,
            preserveState: true,
        });
    },
);

router.on('start', () => {
    showLoading.value = true;
});
router.on('finish', () => {
    showLoading.value = false;
});
</script>

<template>
    <div class="bg-white px-4 py-6 sm:rounded-lg sm:px-6 sm:shadow lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Users</h1>
                <p class="mt-2 text-sm text-gray-700">
                    A list of all the books, including their title, price and
                    users likes.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <PrimaryButton
                    type="button"
                    @click="
                        $emit('edit', {
                            id: 0,
                            title: '',
                            description: '',
                            price: 0,
                            image: '',
                        })
                    "
                >
                    Add book
                </PrimaryButton>
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
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
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
                                        sort-by="title"
                                        @click="sortBy('title')"
                                    >
                                        Title
                                    </SortBy>
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    <SortBy
                                        sort-by="price"
                                        @click="sortBy('price')"
                                    >
                                        Prices
                                    </SortBy>
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                >
                                    <SortBy
                                        sort-by="price"
                                        @click="sortBy('likes')"
                                        class="flex-row-reverse"
                                    >
                                        Likes
                                    </SortBy>
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
                            <tr
                                v-for="book in books.data"
                                :key="`book-${book.id}`"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0"
                                >
                                    {{ book.id }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900"
                                >
                                    <div
                                        class="flex items-center gap-2 overflow-auto"
                                    >
                                        <span>
                                            <img
                                                v-if="book.image"
                                                :src="book.image"
                                                alt="Book cover"
                                                class="h-8 w-8 rounded-md"
                                            />
                                            <span
                                                v-else
                                                class="h-8 w-8 rounded-md bg-gray-200"
                                            ></span>
                                        </span>
                                        <span>
                                            {{ book.title }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                >
                                    {{ toPrice(book.price) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <span class="text-gray-500">{{
                                        book.users_count ?? 'N/A'
                                    }}</span>
                                </td>
                                <td
                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0"
                                >
                                    <button
                                        type="button"
                                        class="text-indigo-600 hover:text-indigo-900"
                                        @click="
                                            $emit('edit', {
                                                id: book.id,
                                                title: book.title,
                                                description: book.description,
                                                price: book.price,
                                                image: book.image,
                                            })
                                        "
                                    >
                                        Edit<span class="sr-only"
                                            >, {{ book.title }}</span
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

        <SimplePaginator v-bind="books" class="mt-6" />
    </div>
</template>
