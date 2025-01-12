<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { AdminHandleBook } from '@/Models';
import { toUrl } from '@/Utils';
import { PhotoIcon } from '@heroicons/vue/24/solid';
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({
    name: 'AdminBookForm',
    inheritAttrs: false,
});

const props = defineProps<{
    book?: AdminHandleBook | null;
}>();

const emits = defineEmits<{
    (event: 'saved'): void;
}>();

const form = useForm(() => ({
    title: props.book?.title ?? '',
    description: props.book?.description ?? '',
    price: props.book?.price ?? 0,
    file: null as File | null,
    _method: props.book?.id ? 'put' : 'post',
}));

const previewUrl = computed<string | null>(() => {
    if (form.file) {
        return URL.createObjectURL(form.file);
    }

    return props.book?.image ? toUrl(props.book.image) : null;
});

function handleUpload(event: Event) {
    const target = event.target as HTMLInputElement;
    form.file = null;
    if (target.files) {
        form.file = target.files[0];
    }
}

function handleSubmit() {
    return form.post(
        props.book?.id
            ? route('admin.books.update', props.book.id)
            : route('admin.books.store'),
        {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                handleReset();
                emits('saved');
            },
        },
    );
}

function handleReset() {
    form.reset();
    form.clearErrors();
    form.title = props.book?.title ?? '';
    form.description = props.book?.description ?? '';
    form.price = props.book?.price ?? 0;
    form.file = null;
}

const showCancelModal = ref(false);

function confirmDelete() {
    router.delete(route('admin.books.destroy', props.book?.id), {
        onSuccess: () => {
            emits('saved');
        },
    });
}
</script>

<template>
    <form @submit.prevent="handleSubmit" @reset.prevent="handleReset">
        <div class="grid items-center gap-6 sm:grid-cols-6">
            <div class="sm:col-span-4">
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                />

                <InputError :message="form.errors.title" class="mt-2" />
            </div>

            <div class="col-span-full">
                <InputLabel for="description" value="Description" />

                <div class="mt-2">
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        name="description"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                </div>

                <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <label
                    for="price"
                    class="block text-sm/6 font-medium text-gray-900"
                    >Price</label
                >
                <div class="mt-2">
                    <div
                        class="flex items-center rounded-md bg-white px-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600"
                    >
                        <div
                            class="shrink-0 select-none text-base text-gray-500 sm:text-sm/6"
                        >
                            €
                        </div>
                        <input
                            id="price"
                            v-model.number="form.price"
                            type="number"
                            class="block min-w-0 grow border-0 py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:border-0 focus:outline focus:outline-0 focus:ring-0 sm:text-sm/6"
                            placeholder="0.00"
                            step="0.01"
                            aria-describedby="price-currency"
                            min="0"
                        />
                        <div
                            id="price-currency"
                            class="shrink-0 select-none text-base text-gray-500 sm:text-sm/6"
                        >
                            Eur
                        </div>
                    </div>
                </div>

                <InputError :message="form.errors.price" class="mt-2" />
            </div>

            <div v-if="previewUrl" class="col-span-full">
                <div
                    class="size-40 rounded-md bg-gray-50 hover:shadow-lg hover:ring-2 hover:ring-gray-200"
                >
                    <img
                        :src="previewUrl"
                        alt="Cover photo"
                        class="h-full w-full rounded-lg object-cover"
                    />
                </div>
                <template v-if="form.file">
                    <h4>{{ form.file.name }}</h4>
                    <span
                        >{{
                            Intl.NumberFormat('eu-IT', {
                                style: 'unit',
                                unit: 'kilobyte',
                                unitDisplay: 'short',
                            }).format(form.file.size)
                        }}
                    </span>
                    <InputError :message="form.errors.file" class="mt-2" />
                </template>
            </div>

            <div class="col-span-full">
                <label
                    for="cover-photo"
                    class="block text-sm/6 font-medium text-gray-900"
                    >Cover image</label
                >
                <div
                    class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
                >
                    <div class="text-center">
                        <PhotoIcon
                            class="mx-auto size-12 text-gray-300"
                            aria-hidden="true"
                        />
                        <div class="mt-4 flex text-sm/6 text-gray-600">
                            <label
                                for="file-upload"
                                class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500"
                            >
                                <span>Upload a file</span>
                                <input
                                    id="file-upload"
                                    name="file-upload"
                                    type="file"
                                    class="sr-only"
                                    accept="image/*"
                                    @input="handleUpload"
                                />
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs/5 text-gray-600">
                            PNG, JPG, GIF up to 10MB
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!previewUrl" class="mt-4">
            <InputError :message="form.errors.file" class="mt-2" />
        </div>

        <progress
            v-if="form.progress"
            :value="form.progress.percentage"
            max="100"
            class="pointer-events-none mt-4 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
            {{ form.progress.percentage }}%
        </progress>

        <div class="mt-4 flex flex-row-reverse gap-4">
            <PrimaryButton type="submit"> Save </PrimaryButton>
            <button
                type="reset"
                class="text-sm/6 text-gray-600 hover:text-gray-900"
            >
                Reset
            </button>

            <button
                type="button"
                class="mr-auto text-sm/6 text-gray-600 hover:text-gray-900"
                @click="showCancelModal = true"
            >
                Delete
            </button>
        </div>
    </form>

    <Modal :show="showCancelModal" @close="showCancelModal = false">
        <div class="p-6">
            <h4 class="text-lg font-semibold">
                You are going to delete permanently this book
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
</template>
