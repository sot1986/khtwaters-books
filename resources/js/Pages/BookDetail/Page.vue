<script setup lang="ts">
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Book } from '@/Models';
import { toPrice } from '@/Utils';
import { CheckIcon } from '@heroicons/vue/20/solid';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    name: 'BookDetail',
    layout: FrontLayout,
    inheritAttrs: false,
});

const props = defineProps<{
    book: Book & {
        creator: {
            id: number;
            name: string;
        };
    };
    can: {
        addToFavorites: boolean;
        removeFromFavorites: boolean;
    };
}>();

const favoriteLink = computed(() => {
    if (props.can.addToFavorites) {
        return {
            url: route('books.addToFavorites', { book: props.book.id }),
            method: 'post' as const,
            label: 'Add to favorites',
        };
    }

    if (props.can.removeFromFavorites) {
        return {
            url: route('books.removeFromFavorites', { book: props.book.id }),
            method: 'delete' as const,
            label: 'Remove from favorites',
        };
    }

    return null;
});

const toggleFavorite = () => {
    if (!favoriteLink.value) {
        return;
    }

    if (favoriteLink.value.method === 'post') {
        return router.post(favoriteLink.value.url, undefined, {
            preserveScroll: true,
        });
    }
    return router.delete(favoriteLink.value.url, {
        preserveScroll: true,
    });
};

function back() {
    window.history.back();
}
</script>

<template>
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:grid lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8"
        >
            <!-- Product details -->
            <div class="lg:max-w-lg lg:self-end">
                <nav aria-label="Breadcrumb">
                    <button type="button" @click="back">Back</button>
                </nav>

                <div class="mt-4">
                    <h1
                        class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        {{ book.title }}
                    </h1>
                </div>

                <section aria-labelledby="information-heading" class="mt-4">
                    <h2 id="information-heading" class="sr-only">
                        Book information
                    </h2>

                    <div class="flex items-center">
                        <p class="text-lg text-gray-900 sm:text-xl">
                            {{ toPrice(book.price) }}
                        </p>
                    </div>

                    <div class="mt-4 space-y-6">
                        <p class="text-base text-gray-500">
                            {{ book.description }}
                        </p>
                    </div>

                    <div class="mt-6 flex items-center">
                        <CheckIcon
                            class="size-5 shrink-0 text-green-500"
                            aria-hidden="true"
                        />
                        <p class="ml-2 text-sm text-gray-500">
                            In stock and ready to ship
                        </p>
                    </div>
                </section>
            </div>

            <!-- Product image -->
            <div
                class="mt-10 lg:col-start-2 lg:row-span-2 lg:mt-0 lg:self-center"
            >
                <img
                    :src="book.image"
                    :alt="book.title"
                    class="aspect-square w-full rounded-lg object-cover"
                />
            </div>

            <!-- Product form -->
            <div
                class="mt-10 lg:col-start-1 lg:row-start-2 lg:max-w-lg lg:self-start"
            >
                <section aria-labelledby="options-heading">
                    <h2 id="options-heading" class="sr-only">
                        Add to you favorites
                    </h2>

                    <form v-if="favoriteLink" @submit.prevent="toggleFavorite">
                        <div class="mt-10">
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                            >
                                {{ favoriteLink.label }}
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</template>
