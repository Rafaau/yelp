<script>
    let data = null;

    async function getReviews() {
        let currentURL = new URL(window.location.href);
        let location = currentURL.pathname.split('/')[1]; 
        console.log(location)

        const res = await fetch(`/reviews/${location}/1`);
        const data = await res.json();

        if (res.ok) {
            console.log(data)
            return data.reviews;
        } else {
            throw new Error(data.message);
        }
    }

    let promise = getReviews();
</script>

{#await promise}
    <p>...waiting</p>
{:then reviews}
    <p>{reviews[0].id}</p>
{:catch error}
    <p style="color: red">{error.message}</p>
{/await}