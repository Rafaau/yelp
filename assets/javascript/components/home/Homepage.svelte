<script lang='ts'>
    import Categories from "./Categories.svelte";
    import Reactions from "../shared/Reactions.svelte";
    import Stars from "../shared/Stars.svelte";

    let currentURL = new URL(window.location.href);
    let location = currentURL.pathname.split('/')[1];

    async function getReviews() {
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

<img
    class="h-[500px] w-full object-cover object-bottom brightness-50" 
    src="build/images/main_1.c44d6871.jpg" 
    alt="404">
<span class="absolute z-10 text-zinc-100 text-5xl font-bold top-48 left-10">
    Connect with great local businesses
</span>    
<p class="text-center text-3xl font-black my-10">
    Recent Activity
</p>
{#await promise then reviews}
    <div class="grid grid-cols-3 gap-8 px-10 pb-10 max-w-[1300px] mx-auto">
        {#each reviews as review}
            <div class="w-[100%] h-[27.5rem] rounded border border-zinc-300 cursor-pointer hover:shadow-md">
                <div class="flex items-center p-4">
                    {#if review.user.userImage}
                        <img src="{review.user.userImage}" alt="404" class="w-12 h-12 rounded-full mr-2" />
                    {:else}
                        <img src="build/images/avatar_default.19e0a8ff.jpg" alt="404" class="w-12 h-12 rounded-full mr-2 border" />
                    {/if}
                    <div>
                        <a
                            href="user_details?userid=${review.user.id}&location=${location}" 
                            class="text-lg font-bold hover:underline">
                            {review.user.username}
                        </a>
                        <p class="text-sm">Wrote a review</p>
                    </div>
                </div>
                {#if review.images.length}
                    <img src="{review.images[0]}" alt="404" class="w-full object-cover h-[30%] my-2" />
                {/if}
                <div class="px-8 py-2">
                    <a
                        href="/biz/{review.business.name}-{location}" 
                        class="text-lg font-bold text-cyan-700 hover:underline">
                        {review.business.name}
                    </a>
                    <div class="flex py-2">
                        <Stars stars={review.stars} />
                    </div>
                    <p class="text-sm dots">{review.content}</p>
                    <div class="flex space-x-2 text-zinc-500 font-semibold text-sm pt-4 mt-3 border-t">
                        <Reactions review={review}/>
                    </div>
                </div>
            </div>
        {/each}
    </div>
{/await}
<p class="text-center text-3xl font-black py-10 border-t">
    Categories
</p>
<div class="max-w-[1300px] mx-auto">
    <Categories location={location} />
</div>