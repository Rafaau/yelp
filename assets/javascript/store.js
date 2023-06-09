import { writable } from 'svelte/store';

export const currentUser = writable(null);

export async function fetchUser() {
    const res = await fetch('/users/currentUser');
    const data = await res.json();

    currentUser.set(data.user);
}

