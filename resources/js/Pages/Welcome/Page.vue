<script setup lang="ts">
import SimplePaginator from '@/Components/SimplePaginator.vue';
import TextInput from '@/Components/TextInput.vue';
import { useSearchQuery } from '@/Composables/useSearch';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import type { Book } from '@/Models';
import type { SimplePaginate } from '@/Types';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: FrontLayout,
});

defineProps<{
    canLogin: boolean;
    canRegister: boolean;
    books: SimplePaginate<Pick<Book, 'id' | 'image' | 'title' | 'price'>>;
    auth: {
        user?: {
            id: number;
            name: string;
            favorites?: {
                id: number;
            }[];
        };
    };
}>();

const { rawSearch } = useSearchQuery();
</script>

<template>
    <Head title="Books" />
    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
        Best Books store
    </h2>

    <div>
        <p class="mt-4 text-lg text-gray-500">
            Welcome to the best books store. Here you can find the best books
            for you.
        </p>

        <div class="mt-6 max-w-sm sm:max-w-md">
            <label
                for="search"
                class="block text-sm/6 font-medium text-gray-900"
                >Quick search</label
            >
            <div class="relative mt-2">
                <div
                    class="pointer-events-none absolute inset-y-0 left-2 flex items-center py-1.5 pr-1.5"
                >
                    <kbd
                        class="inline-flex items-center rounded border border-gray-200 px-1 font-sans text-xs text-gray-400"
                        >⌘K</kbd
                    >
                </div>
                <TextInput v-model="rawSearch" class="w-full pl-12"></TextInput>
            </div>
        </div>
    </div>

    <div
        class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
    >
        <div v-for="book in books.data" :key="book.id" class="group relative">
            <img
                :src="book.image"
                :alt="book.title"
                class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80"
            />
            <div class="mt-4 flex justify-between">
                <div>
                    <h3 class="text-sm text-gray-700">
                        <span aria-hidden="true" class="absolute inset-0" />
                        {{ book.title }}
                    </h3>
                </div>
            </div>
            <div
                class="absolute inset-x-0 bottom-10 flex translate-y-0 justify-center opacity-0 transition group-hover:-translate-y-6 group-hover:opacity-100"
            >
                <Link
                    :href="route('books.show', [book.id])"
                    class="mx-auto rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white"
                >
                    <span>Scopri di +</span>
                </Link>
            </div>
        </div>
    </div>

    <SimplePaginator v-if="!books.links.last" v-bind="books" class="mt-6" />
</template>
