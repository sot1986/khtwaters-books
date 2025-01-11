export interface Model {
    id: number;
    created_at: string;
    updated_at: string;
}

export interface Book extends Model {
    title: string;
    description: string;
    image?: string;
    price: number;
    is_favorite?: boolean;
    creator?: User;
    users_count?: number;
}

export type UserRole = 'admin' | 'user';

export interface User extends Model {
    name: string;
    email: string;
}

export interface AuthUser extends User {
    email_verified_at: string;
    role: UserRole;
}

export type AdminHandleUser = Omit<
    AuthUser,
    'email_verified_at' | 'created_at' | 'updated_at'
>;

export type AdminHandleBook = Pick<
    Book,
    'id' | 'title' | 'description' | 'price' | 'image'
>;
