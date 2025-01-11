<script setup lang="ts">
import AuthenticateLayout from '@/Layouts/AuthenticatedLayout.vue';
import { AdminHandleUser, AuthUser, Book } from '@/Models';
import type { SimplePaginate } from '@/Types';
import { Head, router } from '@inertiajs/vue3';
import { watch } from 'vue';

import Slider from '@/Components/Slider.vue';
import useHeader from '@/Composables/useHeader';
import useSlider from '@/Composables/useSlider';
import UserForm from './Partials/UserForm.vue';
import UsersList from './Partials/UsersList.vue';

defineOptions({
    name: 'AdminUsersPage',
    layout: AuthenticateLayout,
    inheritAttrs: false,
});

const { setHeaderTitle } = useHeader();
setHeaderTitle('All users');

defineProps<{
    users: SimplePaginate<AuthUser>;
    books?: Book[];
}>();

const userSlider = useSlider<AdminHandleUser>();

watch(
    () => userSlider.target?.id,
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
    <Head title="All Users" />

    <UsersList :users @edit="userSlider.open" />

    <Slider
        v-model="userSlider.show"
        :title="userSlider.target ? 'Edit user' : 'Add user'"
        @close="userSlider.close"
    >
        <UserForm
            :user="userSlider.target"
            :books="books"
            @saved="userSlider.close"
        />
    </Slider>
</template>
