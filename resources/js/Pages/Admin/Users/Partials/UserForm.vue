<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { AdminHandleUser, Book } from '@/Models';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    name: 'AdminUserForm',
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<{
        user?: AdminHandleUser | null;
        books?: Book[];
    }>(),
    {
        books: () => [],
    },
);

const emits = defineEmits(['saved']);

const form = useForm({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    role: props.user?.role ?? ('user' as 'user' | 'admin'),
});

const roles = ['user', 'admin'] as const;

function saveUser() {
    const { url, method } =
        (props.user?.id ?? 0) > 0
            ? {
                  url: route('admin.users.update', props.user!.id),
                  method: 'put' as const,
              }
            : { url: route('admin.users.store'), method: 'post' as const };

    return form.submit(method, url, {
        onSuccess: () => {
            form.reset();
            emits('saved');
        },
    });
}

function resetForm() {
    form.reset();
    form.name = props.user?.name ?? '';
    form.email = props.user?.email ?? '';
    form.role = props.user?.role ?? ('user' as 'user' | 'admin');
}

const showCancelModal = ref(false);

function confirmDelete() {
    router.delete(route('admin.users.destroy', props.user?.id), {
        onSuccess: () => {
            emits('saved');
        },
    });
}
</script>

<template>
    <form @submit.prevent="saveUser" @reset.prevent="resetForm">
        <div class="grid items-center gap-6 sm:grid-cols-6">
            <div class="sm:col-span-4">
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="order-1 col-span-full sm:order-2">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />

                <InputError :message="form.errors.email" class="mt-2" />
            </div>

            <fieldset class="order-2 sm:order-1 sm:col-span-2">
                <legend class="sr-only">Role</legend>

                <div
                    class="mt-6 space-y-6 sm:flex sm:items-center sm:space-x-10 sm:space-y-0"
                >
                    <div
                        v-for="role in roles"
                        :key="role"
                        class="flex items-center"
                    >
                        <input
                            :id="role"
                            v-model="form.role"
                            name="application-role"
                            type="radio"
                            :checked="role === form.role"
                            class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden [&:not(:checked)]:before:hidden"
                            :value="role"
                        />
                        <label
                            :for="role"
                            class="ml-3 block text-sm/6 font-medium text-gray-900"
                            >{{ role.toUpperCase() }}</label
                        >
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="mt-8 flex flex-row-reverse items-center gap-6">
            <PrimaryButton type="submit">
                {{ user?.id ? 'Update' : 'Create' }}
            </PrimaryButton>

            <button
                type="reset"
                class="text-sm text-gray-500 hover:text-gray-700"
            >
                Reset
            </button>

            <button
                v-if="user?.id"
                type="button"
                class="text-sm text-gray-500 hover:text-gray-700"
                @click="showCancelModal = true"
            >
                Cancel
            </button>
        </div>
    </form>

    <Modal :show="showCancelModal" @close="showCancelModal = false">
        <div class="p-6">
            <h4 class="text-lg font-semibold">
                You are going to delete permanently this user
            </h4>
            <p class="text-gray-500">Please confirm before proceed</p>

            <form
                class="mt-4 flex gap-6"
                @submit.prevent="showCancelModal = false"
            >
                <button type="submit" @click="showCancelModal = false">
                    Cancel
                </button>
                <DangerButton type="button" @click="confirmDelete"
                    >Confirm</DangerButton
                >
            </form>
        </div>
    </Modal>

    <template v-if="books.length">
        <h2 class="mt-8 text-lg font-semibold">Favorite Books</h2>
        <ul class="mt-4 space-y-1">
            <li
                v-for="book in books"
                :key="book.id"
                class="list-item list-inside list-disc text-sm text-gray-500"
            >
                {{ book.title }}
            </li>
        </ul>
    </template>
</template>
