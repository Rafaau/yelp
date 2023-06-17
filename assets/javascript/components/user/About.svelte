<script lang='ts'>
    import { currentUser } from '../../store.js';
    import AddFriend from './AddFriend.svelte';

    export let user: any;
    export let starsCount: any;
    export let totalReactions: any;
    export let className: string;
</script>

<div class="w-64 {className}">
    <div id="current-user" class="hidden">{user != null ? user.username : ''}</div>
    <div id="friend-id" class="hidden">{user.id}</div>
    <div class="md:border-l border-none md:pl-8 pl-0 md:pb-12 pb-0 md:pt-0 pt-6 flex flex-col text-cyan-700 font-semibold">
        {#if $currentUser != null && $currentUser.id != user.id}
            {#if $currentUser != null && !$currentUser.friends.filter(x => x.id == user.id).length}
                <AddFriend user={user}/>
            {/if}
            <a
                href="{$currentUser != null ? `/messaging/${$currentUser.id}-${user.id}` : '/login'}" 
                class="mb-1 flex items-center cursor-pointer">
                <i class="fa-regular fa-comment text-xs mr-2"></i>
                <span class="hover:underline">Send message</span>
            </a>
            <a class="flex items-center cursor-pointer">
                <i class="fa-solid fa-user-check text-xs mr-2"></i>
                <span class="hover:underline">Follow {user.username}</span>
            </a> 
        {:else}
            <div class="h-20"></div>
        {/if}                        
    </div>
    <div class="mt-10 md:pl-8 pl-0">
        <p class="font-semibold text-lg text-red-600">
            About {user.username}
        </p>
        <p class="text-sm font-semibold my-2">
            Rating distribution
        </p>
        <div class="flex mb-1 text-zinc-600 font-roboto-medium">
            <div class="bg-[#FCD8D2] px-2 rounded mr-3 w-[4.25rem]">
                5 stars
            </div>
            {starsCount[8] + starsCount[9]}
        </div>
        <div class="flex mb-1 text-zinc-600 font-roboto-medium">
            <div class="bg-[#FDE3D0] px-2 rounded mr-3 w-[4.25rem]">
                4 stars
            </div>
            {starsCount[7] + starsCount[8]}
        </div>
        <div class="flex mb-1 text-zinc-600 font-roboto-medium">
            <div class="bg-[#FDE7CF] px-2 rounded mr-3 w-[4.25rem]">
                3 stars
            </div>
            {starsCount[5] + starsCount[6]}
        </div>
        <div class="flex mb-1 text-zinc-600 font-roboto-medium">
            <div class="bg-[#FEEDCE] px-2 rounded mr-3 w-[4.25rem]">
                2 stars
            </div>
            {starsCount[3] + starsCount[4]}
        </div>
        <div class="flex mb-1 text-zinc-600 font-roboto-medium">
            <div class="bg-[#FFF3CD] px-2 rounded mr-3 w-[4.25rem]">
                1 star
            </div>
            {starsCount[1] + starsCount[2]}
        </div>
        <p class="text-sm font-semibold my-3">
            Review Votes
        </p>
        <div class="flex items-center mt-1 text-sm text-zinc-600">
            <i class="fa-regular fa-lightbulb mr-[0.625rem] text-lg ml-[0.125rem]"></i>
            Useful
            <span class="font-semibold ml-2 text-zinc-900">
                {totalReactions['useful']}
            </span>
        </div>
        <div class="flex items-center mt-1 text-sm text-zinc-600">
            <i class="fa-regular fa-face-laugh-squint mr-2 text-lg"></i>
            Funny
            <span class="font-semibold ml-2 text-zinc-900">
                {totalReactions['funny']}
            </span>
        </div>
        <div class="flex items-center mt-1 text-sm text-zinc-600">
            <i class="fa-regular fa-face-grin-wink mr-2 text-lg"></i>
            Cool
            <span class="font-semibold ml-2 text-zinc-900">
                {totalReactions['cool']}
            </span>
        </div>
        <p class="text-sm font-semibold mt-3 mb-1">
            Location
        </p>
        <p class="text-sm font-roboto-light">
            {user.address}
        </p>
        <p class="text-sm font-semibold mt-3 mb-1">
            Whelping Since
        </p>
        <p class="text-sm font-roboto-light">
            {new Date(user.memberSince.date).toLocaleDateString('en-GB', {day: 'numeric', month: 'numeric', year: 'numeric'})}
        </p>
        {#if user.thingsILove != null}
            <p class="text-sm font-semibold mt-3 mb-1">
                Things I Love
            </p>
            <p class="text-sm font-roboto-light">
                {user.thingsILove}
            </p>
        {/if}
        {#if user.myHometown !=null}
            <p class="text-sm font-semibold mt-3 mb-1">
                My Hometown
            </p>
            <p class="text-sm font-roboto-light">
                {user.myHometown}
            </p>
        {/if}
        {#if user.whenIAmNotWhelping != null}
            <p class="text-sm font-semibold mt-3 mb-1">
                When I'm Not Whelping
            </p>
            <p class="text-sm font-roboto-light">
                {user.whenIAmNotWhelping}
            </p>
        {/if}
        {#if user.whyYouShouldRead != null}
            <p class="text-sm font-semibold mt-3 mb-1">
                Why You Should Read My Reviews
            </p>
            <p class="text-sm font-roboto-light">
                {user.whyYouShouldRead}
            </p>
        {/if}
    </div>
</div>