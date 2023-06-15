<script lang='ts'>
    import Stars from "../shared/Stars.svelte";
    import { currentUser } from '../../store.js';
    import Reactions from "../shared/Reactions.svelte";
    import format from 'date-fns/format';
    import parse from 'date-fns/parse';
    import getDay from 'date-fns/getDay';

    let currentURL = new URL(window.location.href);
    let businessName = currentURL.searchParams.get('name');
    let businessLoc = currentURL.searchParams.get('loc');
    let imageCount = 0;

    let currentDay;
    let currentTime;
    let openHours;
    let openingTime;
    let closingTime;

    async function getBusiness() {
        console.log(businessName)
        const res = await fetch(`/businesses/${businessName}-${businessLoc}`);
        const data = await res.json();

        if (res.ok) {
            console.log(data)
            return data.business;
        } else {
            throw new Error(data.message);
        }
    }

    let promise = getBusiness();

    promise.then(business => {   
        imageCount = business.reviews.reduce((acc, review) => acc + review.images.length, 0);

        let now = new Date(); 
            currentDay = format(now, 'iii');
            currentTime = format(now, 'HH:mm');

            openHours = business.hours[currentDay].split(" - ");
            openingTime = convertTo24Hour(String(openHours[0]).split(' - ')[0]);
            closingTime = convertTo24Hour(String(openHours[1]).split(' - ')[0]);
    })

    function convertTo24Hour(timeStr) {
        const parsedTime = parse(timeStr, "hh:mm aa", new Date());
        return format(parsedTime, 'HH:mm');
    }

    function scrollToHours() {
        document.getElementById('hours').scrollIntoView({behavior: 'smooth'});
    }

    function deleteReview(e, reviewId) {
            fetch('/reviews/delete', {
                method: 'POST',
                body: JSON.stringify({
                    id: reviewId,
                })
            }).then(function(response) {
                if (response.ok) {
                    e.target.closest('.review-element').remove();
                }
            });
    }
</script>

{#await promise then business}
<div class="relative top-28 mb-28 flex {imageCount > 3 ? 'h-[450px]' : 'h-[250px]'} brightness-50">
    {#if imageCount > 3}
        {#each business.reviews as review}
            {#each review.images as image}
                <img 
                    class="object-cover"
                    src="{image}">
            {/each}
        {/each}
    {/if}
</div>
<div class="flex max-w-[1300px] mx-auto">
    <div class="absolute z-10 {imageCount > 3 ? 'text-zinc-100 top-[22rem] px-16' : 'text-zinc-900 top-40 px-10'} px-12">
        <p class="text-5xl font-black">{business.name}</p>
        <div class="flex items-center my-4">
            {#if business.reviews.length}
                <Stars stars={business.avgStars}/>
            {/if}
            <span class="font-semibold hover:underline cursor-pointer">
                {business.reviews.length} reviews
            </span>
        </div>
        <div class="flex items-center font-semibold my-1">
            {#if business.owner != null}
                <div id="claimed-info">
                    <i class="fa-solid fa-circle-check text-blue-400 mr-1 text-sm"></i>
                    <span class="text-blue-400 mr-2">
                        Claimed
                    </span>
                </div>
            {:else}
                <div id="unclaimed-info">
                    <span class="mr-1">
                        Unclaimed
                    </span>
                    <i class="fa-solid fa-circle-exclamation mx-2 text-sm"></i>
                </div>
            {/if}
            <span class="mr-2">•</span>           
            {#each Array(business.expensiveness) as i}
                <i class="fa-solid fa-dollar-sign"></i>
            {/each}
            <span class="ml-2">•</span>  
            <a 
                href="{`search?cflt=${business.categories[1].name.toLowerCase()}&find_loc=${businessLoc}`}" 
                class="ml-2 hover:underline cursor-pointer">
                {business.categories[1].name}
            </a>
            <span class="bg-zinc-400 bg-opacity-30 px-2 ml-3 text-sm py-[0.125rem] rounded cursor-pointer hover:bg-opacity-50 transition-[0.5] font-thin">
                Edit
            </span>
        </div>
        {#if currentTime >= openingTime && currentTime <= closingTime}
            <span class="text-green-600 font-semibold text-lg">
                Open
            </span>
        {:else}
            <span class="text-red-400 font-semibold text-lg">
                Closed
            </span>
        {/if}
        <span class="font-semibold ml-2">
            {openHours[0]} - {openHours[1]}
        </span>
        <button
            id="see-hours-button" 
            on:click={scrollToHours}
            class="bg-zinc-400 bg-opacity-30 px-2 ml-2 text-sm py-[0.125rem] rounded hover:bg-opacity-50 transition-[0.5]">
            See hours
        </button>
    </div>
</div>
<div class="flex max-w-[1300px] mx-auto px-10">
    <div class="py-4 pr-10 w-[65%]">
        <p class="text-sm text-zinc-600">
            <a 
                class="hover:underline cursor-pointer"
                href="{`search?cflt=${business.categories[0].name.toLowerCase()}&find_loc=${businessLoc}`}" >
                {business.categories[0].name}
            </a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>
            <a 
                class="hover:underline cursor-pointer"
                href="{`search?cflt=${business.categories[1].name.toLowerCase()}&find_loc=${businessLoc}`}" >
                {business.categories[1].name}
            </a>
            <i class="fa-solid fa-chevron-right mx-2 text-xs"></i>       
            {business.name}
        </p>
        <div class="flex my-8">
            <a 
                href="{$currentUser != null ? `/review?business=${encodeURIComponent(business.name)}&loc=${businessLoc}` : '/login'}"
                class="bg-red-600 text-zinc-100 px-4 py-2 rounded font-semibold">
                <i class="fa-regular fa-star mr-2"></i>
                Write a review
            </a>
            <button class="py-2 px-4 border border-zinc-400 rounded ml-2 hover:bg-zinc-400 hover:bg-opacity-30 transition-[0.5] font-semibold">
                <i class="fa-solid fa-camera-retro mr-2"></i>
                Add Photo
            </button>
            <button class="py-2 px-4 border border-zinc-400 rounded ml-2 hover:bg-zinc-400 hover:bg-opacity-30 transition-[0.5] font-semibold">
                <i class="fa-regular fa-share-from-square"></i>
                Share
            </button>
            <button class="py-2 px-4 border border-zinc-400 rounded ml-2 hover:bg-zinc-400 hover:bg-opacity-30 transition-[0.5] font-semibold">
                <i class="fa-regular fa-bookmark"></i>
                Save
            </button>
        </div>
        <div class="border-b border-t py-8">
            <span
                id="hours" 
                class="font-black text-xl">
                Location & Hours
                <div class="flex mt-6">
                    <div>
                        <img src="https://maps.googleapis.com/maps/api/staticmap?size=315x150&sensor=false&client=gme-yelp&language=en&scale=1&zoom=15&center=51.511835%2C-0.122708&markers=scale%3A1%7Cicon%3Ahttps%3A%2F%2Fyelp-images.s3.amazonaws.com%2Fassets%2Fmap-markers%2Fannotation_32x43.png%7C51.511835%2C-0.122708&signature=ghss--MrHd30bDdO0JRyjz1dY6c="/>
                        <div class="flex mt-4">
                            <div>
                                <p class="font-bold text-cyan-600 block text-sm">
                                    {business.address}
                                </p>
                                <p class="font-bold text-sm">
                                    {business.location}
                                </p>
                            </div>
                            <button class="py-2 px-4 border border-zinc-400 rounded ml-auto hover:bg-zinc-400 hover:bg-opacity-30 transition-[0.5] font-semibold text-sm">
                                Get directions
                            </button>
                        </div>
                    </div>
                    <div class="font-thin text-base ml-8 [&>p]:my-1">
                        {#each Object.entries(business.hours) as [day, hours]}
                            {#if day == currentDay}
                                {#if currentTime >= convertTo24Hour(String(hours).split(' - ')[0]) && currentTime <= convertTo24Hour(String(hours).split(' - ')[1])}
                                    <div class="flex">
                                        <div class="w-12 inline-block">
                                            {day}
                                        </div>
                                        {hours}  
                                        <span class="text-green-600 ml-4">
                                            Open now
                                        </span>
                                    </div>
                                {:else}
                                    <div class="flex">
                                        <div class="w-12 inline-block">
                                            {day}
                                        </div> 
                                        {hours}  
                                        <span class="text-red-600 ml-4">
                                            Closed now
                                        </span>
                                    </div>
                                {/if}
                            {:else}
                                <div class="flex">
                                    <div class="w-12 inline-block">
                                        {day}
                                    </div>   
                                    {hours}
                                </div>
                            {/if}
                        {/each}
                    </div>
                </div>
            </span>
        </div>
            {#if business.owner != null || business.description != null}
                <div class="my-8 pb-8 border-b">
                    <span class="font-black text-xl my-3">
                        About the Business
                    </span>
                    {#if business.owner != null}
                        <div class="flex mt-6 items-center">
                            <img src="../build/images/avatar_default.19e0a8ff.jpg" class="w-12 h-12 rounded-full mr-3 border">
                            <div>
                                <p class="font-semibold">
                                    {business.owner}
                                </p>
                                <p class="text-sm text-zinc-600">
                                    Business Owner
                                </p>
                            </div>
                        </div>
                    {/if}
                    {#if business.description != null}
                        <p class="mt-6 font-roboto-light">
                            {business.description}
                        </p>
                    {/if}
                </div>
            {/if}
        <div class="my-8">
            <span class="font-black text-xl">
                Recommended reviews
            </span>
            {#each business.reviews as review}
                <div class="mt-6 mb-10 review-element">
                    <div class="flex">
                        {#if review.user.userImage}
                            <img src="../{review.user.userImage}" class="w-[4.5rem] h-[4.5rem] rounded-full mr-3 border" alt="404">
                        {:else}
                            <img src="build/images/avatar_default.19e0a8ff.jpg" class="w-[4.5rem] h-[4.5rem] rounded-full mr-3 border" alt="404">
                        {/if}
                        <div>
                            <a 
                                href="{`user_details?userid=${review.user.id}&location=${location}`}"
                                class="font-semibold mt-[0.125rem] cursor-pointer hover:underline">
                                {review.user.username}
                            </a>
                            {#if review.user.address}
                                <p class="text-sm font-roboto-light">
                                    {review.user.address}
                                </p>
                            {/if}
                            <div class="flex items-center text-zinc-600 text-sm mt-1">
                                <i class="fa-solid fa-users-rectangle text-xs"></i>
                                <span class="mx-1">
                                    {review.user.friends.length}
                                </span>
                                <i class="fa-regular fa-message text-xs ml-2"></i>
                                <span class="mx-1">
                                    {review.user.reviews.length}
                                </span>
                                <i class="fa-regular fa-images text-xs ml-2"></i>
                                <span class="mx-1">
                                    {review.user.friends.length}
                                </span>
                            </div>
                        </div>
                        {#if $currentUser != null && review.user.id == $currentUser.id}
                            <button
                                on:click={(e) => deleteReview(e, review.id)}
                                class="ml-auto delete-review-btn">
                                <i class="fa-solid fa-trash-can text-xl text-red-700 cursor-pointer hover:text-red-600"></i>
                            </button>
                        {/if}
                    </div>
                    <div class="flex items-center py-4">
                        <Stars stars={review.stars}/>
                        <span class="text-zinc-600 ml-3 font-roboto-light">
                            {new Date(review.postDate.date).toLocaleDateString('en-GB', {day: 'numeric', month: 'numeric', year: 'numeric'})}
                        </span>
                    </div>
                    {#if review.images.length}
                        <p class="font-semibold text-xs text-zinc-700 mb-4">
                            <i class="fa-solid fa-camera-retro text-xs mr-2"></i>
                            {review.images.length} {review.images.length > 1 ? 'photos' : 'photo'}
                        </p>
                    {/if}
                    <div class="font-roboto-light">
                        {@html review.content.replace(/\n/g, '<br />')}
                    </div>
                    {#if review.images|length}
                    <div class="flex h-36 my-6 space-x-4">
                        {#each review.images as image}
                            <img 
                                class="object-cover h-full w-1/3 rounded-md"
                                src="{image}">
                        {/each}
                    </div>
                    {/if}
                    <div class="flex space-x-2 text-zinc-500 font-semibold text-sm mt-1">
                        <Reactions review={review}/>
                    </div>
                </div>
            {/each}
        </div>
    </div>
    <div class="sticky border px-4 py-4 w-96 h-44 top-6 mb-6 mt-12 ml-auto">
        <a 
            href="{business.website}"
            class="font-bold text-cyan-700 border-b pb-2 flex cursor-pointer">
            {business.website}
            <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-zinc-800 text-2xl"></i>
        </a>
        <p class="border-b py-2 flex">
            {business.phoneNumber}
            <i class="fa-solid fa-phone-volume ml-auto mt-1 text-zinc-800 text-2xl"></i>
        </p>
        <div class="flex items-center pt-2">
            <div>
                <p class="font-bold text-cyan-700">
                    Get Directions
                </p>
                <p class="text-zinc-500">
                    {business.address} {business.location}
                </p>        
            </div>
            <i class="fa-solid fa-diamond-turn-right ml-auto text-zinc-800 text-2xl"></i>
        </div>
    </div>
</div>
{/await}