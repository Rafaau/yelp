import { writable } from 'svelte/store';

export const user = writable(null);

export async function fetchUser() {
    const res = await fetch('/users/currentUser');
    const data = await res.json();

    user.set(data.user);
}

