import { HeaderKey } from '@/InjectionKeys';
import { inject, ref } from 'vue';

export default function () {
    return inject(
        HeaderKey,
        () => ({
            setHeaderTitle: (title: string) => {
                console.log('setHeaderTitle', title);
            },
            headerTitle: ref(''),
        }),
        true,
    );
}
