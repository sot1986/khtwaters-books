import { AuthUserKey } from '@/InjectionKeys';
import { computed, inject } from 'vue';

export default function useAuth() {
    const auth = inject(
        AuthUserKey,
        computed(() => null),
    );

    const isAuthenticated = computed(() => !!auth.value);
    const isAdmin = computed(() => auth.value?.role === 'admin');
    const isUser = computed(() => auth.value?.role === 'user');
    const isGuest = computed(() => !auth.value);

    return {
        auth,
        isAuthenticated,
        isAdmin,
        isUser,
        isGuest,
    };
}
