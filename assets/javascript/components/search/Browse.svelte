<script lang='ts'>
    import Stars from "../shared/Stars.svelte";

    function capitalize(string) {
        return string ? string.charAt(0).toUpperCase() + string.slice(1) : null;
    }

    let currentURL = new URL(window.location.href);
    let cflt = currentURL.searchParams.get('cflt');
    let location = currentURL.searchParams.get('find_loc');
    let findDesc = currentURL.searchParams.get('find_desc');

    async function getBusinesses() {
        console.log('test')
        const res = await fetch(`/businesses?find_loc=${location}&cflt=${cflt}&find_desc=${findDesc}`);
        const data = await res.json();

        if (res.ok) {
            console.log(data)
            return data.businesses;
        } else {
            throw new Error(data.message);
        }
    }

    let promise = getBusinesses();
</script>

{#await promise then businesses}
<div class="relative top-28 mb-28 flex border-b">
    <div class="">
            <div class="px-10 {findDesc == null ? 'py-6' : 'pb-6'} border-b">
                {#if cflt != '' && findDesc == null}
                    <span class="text-zinc-500 text-sm font-semibold">
                        {capitalize(location)} 
                        <i class="fa-solid fa-chevron-right"></i>
                        {capitalize(cflt)}
                    </span>
                {/if}
                {#if findDesc != null}
                    <div class="flex">
                        <div>
                            <p class="font-semibold text-2xl">
                                Find a business to review
                            </p>
                            <p class="font-roboto-light my-6">
                                Review anything from your favourite patio spot to your local flower shop.
                            </p>
                            <div class="flex bg-white pl-3 py-3 mb-6 rounded-md relative w-[40vw] shadow-md">
                                <input 
                                    id="cflt-input"
                                    type="text" 
                                    class="bg-transparent outline-none border-r border-zinc-300 pr-2 w-[45%]"
                                    placeholder="pizza, pub, Fox & Hound"
                                    value="{cflt ? capitalize(cflt) : ''}"/>
                                <input 
                                    id="loc-input"
                                    type="text" 
                                    class="bg-transparent outline-none pl-2 w-[45%] mr-[12%] text-zinc-900"
                                    placeholder="London"
                                    value="{capitalize(location)}"/>
                                <div 
                                    id="write-review-btn"
                                    class="absolute items-center flex justify-center right-[-1%] top-0 bg-red-600 w-14 h-full rounded-r-md cursor-pointer">
                                    <i class="fa-solid fa-magnifying-glass text-2xl text-zinc-100"></i>
                                </div>
                            </div>
                        </div>
                        <img 
                            src="https://s3-media0.fl.yelpcdn.com/assets/public/first_to_review_375x200_v2.yji-1aed85a8933f1f907aba.svg"
                            alt="404"
                            class="ml-auto w-[30vw]"/>
                    </div>
                {/if}
                <div class="flex items-center w-[60%]">
                    <span class="font-extrabold block text-2xl">
                        {#if cflt != '' && findDesc == null}
                            Top {businesses.length >= 10 ? 10 : businesses.length} {capitalize(cflt)} in {capitalize(location)}
                        {:else if findDesc != null}
                            Select the business you’d like to review
                        {:else}
                            Browsing {capitalize(location)} businesses
                        {/if}
                    </span>
                    <span class="ml-auto">
                        Sort: 
                        <span class="font-semibold cursor-pointer hover:underline">
                            Highest Rated
                            <i class="fa-solid fa-chevron-down"></i>  
                        </span>                
                    </span>
                </div>
                <div class="sticky ">
                </div>
            </div>
        <div class="px-16 py-4 min-h-[50vh] w-[70%]">
            {#if cflt != ''}
                <span class="font-bold my-4">
                    All "{capitalize(cflt)}" results in {capitalize(location)}
                </span>
            {/if}
            {#each businesses as business, index}
                <a 
                    href="{findDesc != null ? `/review?business=${encodeURIComponent(business.name)}` : `/biz?name=${encodeURIComponent(business.name)}&loc=${location}` }"
                    class="flex my-5 pb-5 border-b cursor-pointer">
                    {#if business.reviews.length && business.reviews[0] != null && business.reviews[0].images != null && business.reviews[0].images[0] != null}
                        <img src="{business.reviews[0].images[0]}" alt="" class="w-48 h-48 object-cover rounded-md">
                    {:else}
                        <div class="w-48"></div>
                    {/if}
                    <div class="ml-6">
                        <span class="font-bold text-xl">
                            {index + 1}. <span class="hover:underline">{business.name}</span>
                        </span>
                        <div class="flex items-center text-zinc-500 my-1">
                            {#if business.reviews.length}
                                <Stars stars={4}/>
                            {/if}
                            <span>
                                {business.reviews.length} reviews
                            </span>
                        </div>
                        <div class="flex my-2">
                            {#each business.categories as category}
                                <div class="bg-zinc-200 px-2 mr-2 text-sm text-zinc-500 rounded font-semibold hover:bg-zinc-300 transition-[0.2]">
                                    {category.name}                                       
                                </div>
                            {/each}
                        </div>               

                        {#if business.reviews.length}
                            <div class="flex text-zinc-500 mt-2">
                                <i class="fa-regular fa-message mt-1"></i>
                                    <span class="dots ml-2 text-sm">
                                        {business.reviews[0].content}
                                    </span>
                            </div>
                        {/if}
                    </div>
                </a>
            {/each}
        </div>
    </div>
    <div>

    </div>
</div>
{/await}
<div>
</div>