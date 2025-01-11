<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { AuthUserKey } from '@/InjectionKeys';
import { AuthUser } from '@/Models';
import { Link } from '@inertiajs/vue3';
import { computed, provide } from 'vue';

defineOptions({
    name: 'FrontLayout',
    inheritAttrs: false,
});

const props = defineProps<{
    auth: {
        user: AuthUser | null;
    };
}>();

const user = computed(() => props.auth.user);
provide(AuthUserKey, user);
</script>

<template>
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl px-4 py-6 sm:px-6 sm:py-10 lg:max-w-7xl lg:px-8"
        >
            <header class="flex flex-wrap justify-between gap-10">
                <Link :href="route('welcome')">
                    <ApplicationLogo
                        class="h-20 w-20 fill-current text-gray-500"
                    />
                </Link>

                <nav v-if="!user">
                    <Link
                        :href="route('login')"
                        class="text-sm text-gray-600 underline"
                        >Login</Link
                    >
                    <Link
                        :href="route('register')"
                        class="ml-4 text-sm text-gray-600 underline"
                        >Register</Link
                    >
                </nav>
                <nav v-else class="flex items-center gap-2">
                    <Link
                        v-if="user.role === 'admin'"
                        :href="route('admin.dashboard')"
                        class="text-sm text-gray-600 underline"
                    >
                        Admin
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('profile.edit')"
                            class="text-sm text-gray-600 underline"
                        >
                            Profile
                        </Link>
                        <Link
                            :href="route('books.favorites')"
                            class="text-sm text-gray-600 underline"
                        >
                            My Favorites
                        </Link>
                    </template>
                </nav>
            </header>
            <main class="mt-10">
                <slot />
            </main>
        </div>
    </div>
</template>
