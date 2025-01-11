<script setup lang="ts">
import Slider from '@/Components/Slider.vue';
import useHeader from '@/Composables/useHeader';
import useSlider from '@/Composables/useSlider';
import AuthenticateLayout from '@/Layouts/AuthenticatedLayout.vue';
import { AdminHandleBook, Book } from '@/Models';
import { SimplePaginate } from '@/Types';
import { Head, router } from '@inertiajs/vue3';
import { watch } from 'vue';
import BookForm from './Partials/BookForm.vue';
import BooksList from './Partials/BooksList.vue';

defineOptions({
    name: 'AdminBooksPage',
    layout: AuthenticateLayout,
    inheritAttrs: false,
});

const { setHeaderTitle } = useHeader();
setHeaderTitle('All books');

defineProps<{
    books: SimplePaginate<Book>;
}>();

const bookSlider = useSlider<AdminHandleBook>();

watch(
    () => bookSlider.target?.id,
    (id) => {
        router.reload({
            only: ['books'],
            data: {
                focusId: id ?? undefined,
            },
        });
    },
);
</script>

<template>
    <Head title="All Books" />

    <BooksList :books @edit="bookSlider.open" />

    <Slider
        v-model="bookSlider.show"
        :title="bookSlider.target ? 'Edit book' : 'Add book'"
        @close="bookSlider.close"
    >
        <BookForm :book="bookSlider.target" @saved="bookSlider.close" />
    </Slider>
</template>
