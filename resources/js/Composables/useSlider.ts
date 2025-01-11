import { reactive } from 'vue';

export interface SliderI<T> {
    show: boolean;
    target: T | null;
    open: (value: T) => Promise<void>;
    close: () => Promise<void>;
}

export default function <T>(cb?: {
    open?: (value: T) => void | Promise<void>;
    close?: () => void | Promise<void>;
}) {
    const slider = reactive({
        show: false,
        target: null as null | T,
        open,
        close,
    }) as SliderI<T>;

    async function open(value: T) {
        if (slider.show) {
            return Promise.resolve();
        }

        if (cb?.open) {
            await cb.open(value);
        }
        slider.target = value as T;
        slider.show = true;
    }

    async function close() {
        if (!slider.show) {
            return Promise.resolve();
        }

        if (cb?.close) {
            await cb.close();
        }
        slider.show = false;
        slider.target = null;
    }

    return slider;
}
