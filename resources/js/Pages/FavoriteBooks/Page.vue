<script setup lang="ts">
import FrontLayout from '@/Layouts/FrontLayout.vue';
import type { Book } from '@/Models';
import { toDateTime } from '@/Utils';
import { TrashIcon } from '@heroicons/vue/20/solid';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    name: 'FavoriteBooksPage',
    layout: FrontLayout,
});

defineProps<{
    books: Book[];
}>();
</script>

<template>
    <Head title="Favorite Books" />

    <div class="px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            All Favorite Books
        </h1>

        <p class="mt-4 text-lg text-gray-500">
            Here you can find all your favorite books.
        </p>

        <ul
            role="list"
            class="mx-auto mt-10 max-w-full divide-y divide-gray-100 sm:max-w-2xl"
        >
            <li v-for="book in books" :key="book.id" class="flex gap-x-4 py-5">
                <img
                    class="size-12 flex-none rounded-full bg-gray-50"
                    :src="book.image"
                    :alt="book.title"
                />
                <div class="flex-auto">
                    <div
                        class="flex flex-col items-baseline justify-between gap-x-4 sm:flex-row"
                    >
                        <Link
                            class="line-clamp-2 text-sm/6 font-semibold text-gray-900 hover:text-indigo-700 hover:underline"
                            :href="route('books.show', { book: book.id })"
                        >
                            {{ book.title }}
                        </Link>
                        <p
                            class="flex flex-wrap gap-x-2 gap-y-1 text-xs text-gray-600"
                        >
                            <time :datetime="book.created_at">{{
                                toDateTime(book.created_at)
                            }}</time>

                            <span class="text-gray-900">{{
                                book.creator?.name
                            }}</span>
                        </p>
                    </div>
                    <p class="mt-1 line-clamp-2 text-sm/6 text-gray-600">
                        {{ book.description }}
                    </p>
                </div>

                <Link
                    as="button"
                    type="button"
                    :href="
                        route('books.removeFromFavorites', { book: book.id })
                    "
                    method="delete"
                    class="flex-none text-sm text-gray-600 underline hover:text-red-600"
                    preserve-scroll
                >
                    <span class="sr-only">Remove {{ book.title }}</span>
                    <TrashIcon class="size-5" aria-hidden="true" />
                </Link>
            </li>
        </ul>
    </div>
</template>
