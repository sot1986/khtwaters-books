import { AuthUserKey } from '@/InjectionKeys';
import { AuthUser } from '@/Models';
import { computed, ComputedRef, inject } from 'vue';

export default function useAuth<TAuth extends boolean = false>(force?: TAuth) {
    function dummyUser() {
        const time = new Date().toISOString();
        return {
            id: 1,
            name: 'Guest',
            email: 'fake@email.com',
            role: 'user',
            created_at: time,
            updated_at: time,
            email_verified_at: time,
        };
    }

    const auth = inject(
        AuthUserKey,
        () => computed(() => (force ? dummyUser() : null)),
        true,
    ) as ComputedRef<TAuth extends true ? AuthUser : AuthUser | null>;

    const isAuthenticated = computed(
        () => !!auth.value as TAuth extends true ? true : boolean,
    );
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
