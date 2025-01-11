import type { ComputedRef, InjectionKey, Ref } from 'vue';
import { AuthUser } from './Models';

export const AuthUserKey: InjectionKey<ComputedRef<AuthUser | null>> =
    Symbol('AuthUser');

export const HeaderKey: InjectionKey<{
    headerTitle: Ref<string>;
    setHeaderTitle: (value: string) => void;
}> = Symbol('HeaderTitle');

export interface SortByI {
    sortParams: ComputedRef<{
        sort: string;
        direction: 'asc' | 'desc';
    }>;
}

export const sortByKey = Symbol('sort by') as InjectionKey<SortByI>;
