<script setup lang="ts">
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

defineOptions({
    name: 'UiSlider',
    inheritAttrs: false,
});

const props = defineProps<{
    modelValue: boolean;
    title: string;
    description?: string;
    onClose?: () => unknown;
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

function close() {
    emits('update:modelValue', false);
    return (props.onClose ?? (() => null))();
}
</script>

<template>
    <TransitionRoot as="template" :show="modelValue">
        <Dialog as="div" class="relative z-40" @close="close">
            <div class="fixed inset-0" />

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16"
                    >
                        <TransitionChild
                            as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-from="translate-x-full"
                            enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-x-0"
                            leave-to="translate-x-full"
                        >
                            <DialogPanel
                                class="pointer-events-auto w-screen max-w-2xl"
                            >
                                <div
                                    class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl"
                                >
                                    <div class="flex flex-1 flex-col">
                                        <!-- Header -->
                                        <div
                                            class="bg-gray-50 px-4 py-6 sm:px-6"
                                        >
                                            <div
                                                class="flex items-start justify-between space-x-3"
                                            >
                                                <div class="space-y-1">
                                                    <DialogTitle
                                                        class="text-base font-semibold leading-6 text-gray-900"
                                                    >
                                                        {{ title }}
                                                    </DialogTitle>
                                                    <p
                                                        v-if="description"
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{ description }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="flex h-7 items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class="relative text-gray-400 hover:text-gray-500"
                                                        @click="close"
                                                    >
                                                        <span
                                                            class="absolute -inset-2.5"
                                                        />
                                                        <span class="sr-only"
                                                            >Close panel</span
                                                        >
                                                        <XMarkIcon
                                                            class="h-6 w-6"
                                                            aria-hidden="true"
                                                        />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-1 px-4 py-6 sm:px-6">
                                            <slot />
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
