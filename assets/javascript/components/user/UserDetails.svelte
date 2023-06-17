<script lang='ts'>
    import { currentUser } from '../../store.js';
    import Reactions from "../shared/Reactions.svelte";
    import Stars from "../shared/Stars.svelte";
    import About from './About.svelte';
    import AddFriend from './AddFriend.svelte';

    let currentURL = new URL(window.location.href);
    let userId = currentURL.searchParams.get('userid');

    async function getUser() {
        const res = await fetch(`/users/${userId}`);
        const data = await res.json();

        if (res.ok) {
            console.log(data)
            return data.user;
        } else {
            throw new Error(data.message);
        }
    }

    let promise = getUser();

    let totalReactions = { useful: 0, funny: 0, cool: 0 };
    let starsCount = {1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0, 8: 0, 9: 0, 10: 0};
    let views = {profile: 0, reviews: 1, friends: 2};
    let currentView = views.profile;

    promise.then(user => {
        user.reviews.forEach(review => {
            for (let reaction in review.reactions) {
                if (totalReactions.hasOwnProperty(reaction)) {
                    totalReactions[reaction] += review.reactions[reaction];
                }
            }
        });

        user.reviews.forEach(review => {
            const star = review.stars;
            if (star in starsCount) {
                starsCount[star] += 1;
            } else {
                starsCount[star] = 1;
            }
        });
    });
</script>

{#await promise then user}
<img 
    class="absolute sm:top-14 top-28 h-[14.5rem] w-full object-cover object-bottom"
    src="https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_user_details/3a3ed7e5327e/assets/img/stars-static.png">
<div class="w-full flex justify-center">
    <div class="relative flex sm:top-36 top-52 mb-44 px-24 max-w-[1300px]">
        <div class="min-w-[14rem]">
            {#if user.userImage}
                <img 
                    src="{user.userImage}" 
                    alt="404" 
                    class="rounded-md w-56 h-56">
            {:else}
                <img 
                    src="https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_styleguide/7e4e0dfd903f/assets/img/default_avatars/user_large_square.png"
                    alt="404" 
                    class="rounded-md w-56 h-56 border-2">
            {/if}
            <p class="my-4 font-semibold text-lg">
                {user.username.split(' ')[0]}'s Profile
            </p>
            <div class="flex flex-col text-zinc-600">
                <div
                    on:click={() => currentView = views.profile}
                    on:keydown={null} 
                    class="py-3 px-3 border-b border-t font-roboto-light cursor-pointer flex items-center {currentView == views.profile ? 'border-l-red-600 border-l-4' : 'border-l-4 border-l-transparent'}">
                    <i class="fa-regular fa-circle-user text-lg mr-3"></i>
                    Profile Overview
                </div>
                <div 
                    on:click={() => currentView = views.friends}
                    on:keydown={null} 
                    class="py-3 px-3 border-b font-roboto-light cursor-pointer flex items-center {currentView == views.friends ? 'border-l-red-600 border-l-4' : 'border-l-4 border-l-transparent'}">
                    <i class="fa-solid fa-users-rectangle text-lg mr-3"></i>
                    Friends
                </div>
                <div
                    on:click={() => currentView = views.reviews}
                    on:keydown={null}  
                    class="py-3 px-3 border-b font-roboto-light cursor-pointer flex items-center {currentView == views.reviews ? 'border-l-red-600 border-l-4' : 'border-l-4 border-l-transparent'}">
                    <i class="fa-regular fa-message text-lg mr-4"></i>
                    Reviews
                </div>
            </div>
            <About user={user} starsCount={starsCount} totalReactions={totalReactions} className="md:hidden block"/>
        </div>
        <div class="px-8 max-w-[60%]">
            <div class="border-b">
                <p 
                    id="user-firstname"
                    class="text-3xl font-black mb-1 flex items-center">
                    {user.username}
                    {#if $currentUser != null && $currentUser.friends.filter(x => x.id == user.id).length}
                        <span class="text-base text-green-600 ml-auto">Friend</span>
                    {/if}
                    <span id="friend-span" class="text-base text-green-600 ml-auto hidden">Friend</span>
                </p>
                {#if user.address != null}
                    <p>
                        From {user.address}
                    </p>
                {/if}
                <div class="flex items-center text-zinc-800 font-roboto-light text-base mt-1">
                    <i class="fa-solid fa-users-rectangle text-lg text-orange-600"></i>
                    <span class="mx-1 font-semibold">
                        {user.friends.length}
                    </span>
                    Friends
                    <i class="fa-regular fa-message text-lg text-orange-600 ml-4"></i>
                    <span class="mx-1 font-semibold">
                        {user.reviews.length}
                    </span>
                    Reviews
                    <i class="fa-regular fa-images text-lg text-orange-600 ml-4"></i>
                    <span class="mx-1 font-semibold">
                        {user.friends.length}
                    </span>
                    Photos
                </div>
                {#if user.description != null}
                    <p class="my-3">
                        "{user.description}"
                    </p>
                {/if}
            </div>
            {#if user.reviews.length}
                <p class="font-semibold text-xl text-red-600 py-6 border-b">
                    Reviews
                </p>
                {#each user.reviews as review}
                    <div class="py-4">
                        <div class="flex">
                            <img src="{review.business.reviews[0].images[0]}" alt="logo" class="rounded-md w-16 h-16 object-cover border">
                            <div class="ml-2">
                                <a 
                                    href="/biz/{review.business.name}-{location}"
                                    class="font-semibold text-cyan-700 cursor-pointer hover:underline">
                                    {review.business.name}
                                </a>
                                <p class="flex items-center">
                                    {#each Array(review.business.expensiveness) as i}
                                        <i class="fa-solid fa-dollar-sign text-xs text-zinc-700 mr-1"></i>
                                    {/each}
                                    •
                                    {#each review.business.categories as category, i}
                                        <a 
                                            href="{`search?cflt=${category.name.toLowerCase()}&find_loc=${location}`}"
                                            class="text-cyan-700 ml-1 text-xs">
                                            {category.name}
                                        </a>
                                        {#if i == review.business.categories.length - 1}
                                            <span class="text-sm">,</span>
                                        {/if}
                                    {/each}                                   
                                </p>
                                <p class="text-xs font-roboto-light">
                                    {review.business.address}                                   
                                </p>
                                <p class="text-xs font-roboto-light">
                                    {review.business.location}                                   
                                </p>
                            </div>
                        </div>
                        <div class="flex py-3">
                            <Stars stars={review.stars}/>
                            <span class="font-roboto-light text-sm text-zinc-600 ml-2">
                                {new Date(review.postDate.date).toLocaleDateString('en-GB', {day: 'numeric', month: 'numeric', year: 'numeric'})}
                            </span>                                                                                  
                        </div>
                        <p class="font-roboto-light text-sm">
                            {@html review.content.replace(/\n/g, '<br />')}
                        </p>
                        <p class="font-semibold text-xs text-zinc-600 mt-3">
                            Was this review …?
                        </p>
                        <div class="flex space-x-2 text-zinc-500 font-semibold text-sm mt-1">
                            <Reactions review={review}/>
                        </div>
                    </div>
                {/each}
            {/if}
        </div>
        <About user={user} starsCount={starsCount} totalReactions={totalReactions} className="md:block hidden"/>
    </div>
</div>
{/await}