<script lang="ts">
    import { currentUser } from '../../store.js';
    import {clickOutside} from '../../libs/clickOutside.js';
    import Categories from './Categories.svelte';
    import { capitalize, redirect } from '../../libs/utils.js';
    import Search from './Search.svelte';
    import { tooltip } from 'svooltip';
	import 'svooltip/styles.css';

    let currentURL = new URL(window.location.href);
    let cflt = capitalize(currentURL.searchParams.get('cflt'));
    let homeLoc = capitalize(currentURL.pathname.split('/')[1]);
    let findLoc = capitalize(currentURL.searchParams.get('find_loc'));
    let bizLoc = capitalize(currentURL.searchParams.get('loc'));
    let findDesc = currentURL.searchParams.get('find_desc');
    let business = currentURL.pathname.includes('biz');
    let user = currentURL.pathname.includes('user_details');
    let review = currentURL.pathname.includes('review');
    let claim = currentURL.pathname.includes('claim');
    let auth = currentURL.pathname.includes('login') || currentURL.pathname.includes('signup');
    let messaging = currentURL.pathname.includes('messaging');

    let findLocInput = findLoc || bizLoc || homeLoc;

    console.log(cflt, findLoc, findDesc, business)

    let blankView = findLoc != null && findDesc != null || review || claim || auth || messaging;
    let transparentView = cflt == null || findLoc == null;
    let whiteView = cflt != null || findLoc != null || business || user;
    let fixed = cflt != null && findDesc == null;
    let padding = findDesc != null || (cflt == null && business) || review || claim || auth || messaging;

    let dropdown = {
        'Restaurants': false,
        'Home Services': false,
        'Auto Services': false,
        'More': false,
        'Business': false,
        'Notifications': false,
        'User': false
    }

    $: console.log($currentUser);

    function markNotificationsAsRead() {
        dropdown['Notifications'] = !dropdown['Notifications'];
        fetch('/notifications/mark-as-read', {
            method: 'POST'
        }).then(function(response) {
            if (response.ok) {
                console.log('ok')
                document.getElementById('notifications-count').remove();
            }
        })
    }
</script>

<header class="{ whiteView || blankView ? 'bg-zinc-100 border-b': '' } { fixed ? 'fixed' : 'absolute' } z-20 px-10 pt-4 w-full { padding ? 'pb-4' : '' }">
    <div class="flex items-center { whiteView || blankView ? 'text-zinc-900' : 'text-zinc-100' }">
        <a 
            href="/"
            class="logo text-3xl mr-8 cursor-pointer">
            whelp
        </a>
        {#if !blankView }
            <Search cflt={cflt} findLocInput={findLocInput} className="sm:flex hidden"/>
            <span
                on:click={() => dropdown['Business'] = !dropdown['Business']}
                on:keydown={null}
                use:clickOutside
                on:click_outside={() => dropdown['Business'] = false}           
                class="relative text-md cursor-pointer rounded-md py-2 px-3 hover:bg-zinc-400 hover:bg-opacity-30 ml-auto lg:block hidden text-center">
                Whelp for business
                <i class="fa-solid fa-chevron-down ml-1"></i>
                {#if dropdown['Business']}
                    <div class="absolute z-20 top-12 w-48 bg-zinc-100 right-0 py-3 px-3 rounded-lg text-zinc-900 border shadow-md">
                        <a 
                            class="flex items-center hover:bg-zinc-200 rounded p-1" 
                            href="{ $currentUser != null ? '/claim' : '/login'}">
                            <i class="fa-solid fa-store"></i>
                            <span class="ml-2">Add a Business</span>
                        </a>
                    </div>
                {/if}
            </span>
            <a 
                class="text-md ml-3 cursor-pointer rounded-md py-2 px-3 hover:bg-zinc-400 hover:bg-opacity-30 { currentUser != null ? 'write-review-btn' : 'login-btn' } lg:block hidden text-center"
                href={ $currentUser != null ? `../search?cflt=&find_desc=writereview&find_loc=${findLoc || homeLoc}` : '/login'}>
                Write a review
            </a>
            {#if $currentUser != null }
                <a
                    id="messages-btn" 
                    href="/messaging"
                    class="lg:ml-0 ml-auto"
                    use:tooltip={{
                        content: "<span class='z-50'>Messages</span>",
                        html: true,
                    }}>
                    <i class="fa-regular fa-comment-dots text-2xl ml-3 cursor-pointer rounded-full py-2 px-3 hover:bg-zinc-400 hover:bg-opacity-30"></i>
                </a>
                <div
                    on:click={() => markNotificationsAsRead()}
                    on:keydown={null}
                    use:clickOutside
                    on:click_outside={() => dropdown['Notifications'] = false}      
                    id="notifications-btn"                   
                    class="relative"
                    use:tooltip={{
                        content: "<span class='z-50'>Notifications</span>",
                        html: true,
                    }}>
                    <i class="fa-regular fa-bell text-2xl ml-3 cursor-pointer rounded-full py-2 px-3 hover:bg-zinc-400 hover:bg-opacity-30"></i>
                    {#if $currentUser != null && $currentUser.unreadNotifications.length > 0 }
                        <span
                            id="notifications-count" 
                            class="rounded-full bg-red-600 w-5 h-5 text-center absolute left-[65%] text-sm">
                            { $currentUser.unreadNotifications.length }
                        </span>
                    {/if}
                    {#if dropdown['Notifications']}
                        <div 
                            class="absolute z-20 top-12 w-64 bg-zinc-100 right-0 py-3 px-3 rounded-lg text-zinc-900 border shadow-md">
                            {#if $currentUser != null && $currentUser.notifications.length > 0}
                                {#each $currentUser.notifications as notification }
                                    <div class="my-2">
                                        <div class="font-semibold flex items-center">
                                            { notification.title }
                                            {#if !notification.isRead }
                                                <span class="bg-red-600 px-2 py-1 text-zinc-100 font-semibold ml-auto rounded text-sm">New</span>
                                            {/if}
                                        </div>
                                        <p class="text-sm">
                                            { notification.message }
                                        </p>
                                    </div>
                                {/each}
                            {:else }
                                <span class="text-center">No notifications</span>
                            {/if}
                        </div>
                    {/if}
                </div>
                <div
                    on:click={() => dropdown['User'] = !dropdown['User']}
                    on:keydown={null}
                    use:clickOutside
                    on:click_outside={() => dropdown['User'] = false}
                    use:tooltip={{
                        content: `<span class='z-50'>${$currentUser.username}</span>`,
                        html: true,
                    }}     
                    id="user-panel"
                    class="relative">
                    <img
                        class="rounded-full w-10 h-10 ml-4 cursor-pointer border" 
                        src="build/images/avatar_default.19e0a8ff.jpg"
                        alt="404">
                    {#if dropdown['User']}
                        <div class="absolute z-20 top-12 w-40 bg-zinc-100 right-0 py-3 px-3 rounded-lg text-zinc-900 border shadow-md">
                            <a 
                                class="flex items-center hover:bg-zinc-200 rounded p-1" 
                                href="/logout">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="ml-2">Log Out</span>
                            </a>
                        </div>
                    {/if}
                </div>
            {:else}
                <button 
                    on:click={() => redirect('/login')}
                    class="py-2 px-4 border { whiteView ? 'border-zinc-400' : 'border-zinc-100' } rounded-md hover:bg-zinc-400 hover:bg-opacity-30 lg:ml-3 ml-auto">
                    Log In
                </button>
                <button
                    on:click={() => redirect('/signup')}
                    class="py-2 px-4 bg-red-600 text-zinc-100 rounded-md ml-3">
                    Sign up
                </button>
            {/if}
            {/if}
        </div>
        {#if !blankView}
            <Search cflt={cflt} findLocInput={findLocInput} className="sm:hidden flex w-full mt-4 z-10"/>
            <div class="sm:flex hidden items-center text-sm { whiteView ? 'text-zinc-900' : 'text-zinc-100' }">
                <div 
                    on:mouseover={() => dropdown['Restaurants'] = true}
                    on:focus={() => dropdown['Restaurants'] = true}
                    on:blur={() => dropdown['Restaurants'] = false}
                    on:mouseleave={() => dropdown['Restaurants'] = false}
                    class="relative text-md cursor-pointer py-3 px-3 sm:ml-[10vw] ml-0 mr-2 border-b-4 border-transparent hover:border-red-600 flex items-center">
                    Restaurants
                    <i class="fa-solid fa-chevron-down ml-1"></i>
                    {#if dropdown['Restaurants']}
                        <div class="absolute z-20 w-[270px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                            <Categories label="Restaurants" findLoc={findLocInput}/>
                        </div>
                    {/if}
                </div>
                <div 
                    on:mouseover={() => dropdown['Home Services'] = true}
                    on:focus={() => dropdown['Home Services'] = true}
                    on:blur={() => dropdown['Home Services'] = false}
                    on:mouseleave={() => dropdown['Home Services'] = false}
                    class="relative text-md cursor-pointer py-3 px-3 mr-2 border-b-4 border-transparent hover:border-red-600 flex items-center text-center">
                    Home Services
                    <i class="fa-solid fa-chevron-down ml-1"></i>
                    {#if dropdown['Home Services']}
                        <div class="absolute z-20 w-[300px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                            <Categories label="Home Services" findLoc={findLocInput}/>
                        </div>
                    {/if}
                </div>
                <div 
                    on:mouseover={() => dropdown['Auto Services'] = true}
                    on:focus={() => dropdown['Auto Services'] = true}
                    on:blur={() => dropdown['Auto Services'] = false}
                    on:mouseleave={() => dropdown['Auto Services'] = false} 
                    class="relative text-md cursor-pointer py-3 px-3 mr-2 border-b-4 border-transparent hover:border-red-600 flex items-center text-center">
                    Auto Services
                    <i class="fa-solid fa-chevron-down ml-1"></i>
                    {#if dropdown['Auto Services']}
                        <div class="absolute z-20 w-[300px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                            <Categories label="Auto Services" findLoc={findLocInput}/>
                        </div>
                    {/if}
                </div>
                <div 
                    on:mouseover={() => dropdown['More'] = true}
                    on:focus={() => dropdown['More'] = true}
                    on:blur={() => dropdown['More'] = false}
                    on:mouseleave={() => dropdown['More'] = false} 
                    class="relative text-md cursor-pointer py-3 px-3 mr-2 border-b-4 border-transparent hover:border-red-600 flex items-center">
                    More
                    <i class="fa-solid fa-chevron-down ml-1"></i>
                    {#if dropdown['More']}
                        <div class="absolute z-20 w-[300px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                            <Categories label="More" findLoc={findLocInput}/>
                        </div>
                    {/if}
                </div>
            </div>
        {/if}
</header>