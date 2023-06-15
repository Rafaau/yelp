<script>
    import { onMount } from 'svelte';
    import { fetchUser } from '../store.js';

    onMount(async () => {
        await fetchUser();
    })
</script>
