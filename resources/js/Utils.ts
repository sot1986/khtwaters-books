export function toPrice(value: number): string {
    return Intl.NumberFormat('eu-IT', {
        style: 'currency',
        currency: 'EUR',
    }).format(value);
}

export function toDateTime(value: string): string {
    return Intl.DateTimeFormat('eu-IT', {
        dateStyle: 'short',
    }).format(new Date(value));
}

export function toUrl(value: string): string {
    if (value.startsWith('http')) {
        return value;
    }

    return `/${value}`;
}
