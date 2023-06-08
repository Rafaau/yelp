<script>
    import { user } from '../../store.js';

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    let currentURL = new URL(window.location.href);
    let cflt = currentURL.searchParams.get('cflt');
    let findLoc = currentURL.searchParams.get('find_loc');
    let findDesc = currentURL.searchParams.get('find_desc');
    let business = currentURL.pathname.includes('biz');

    console.log(cflt, findLoc, findDesc, business)
    let blankView = findLoc != null && findDesc != null;
    let transparentView = cflt == null || findLoc == null;
    let whiteView = cflt != null || findLoc != null;
    let fixed = cflt != null && findDesc == null;
    let padding = findDesc != null || (cflt == null && business != null);

    $: console.log($user);
</script>

<header class="{ whiteView ? 'bg-zinc-100 border-b': '' } { fixed ? 'fixed' : 'absolute' } z-20 px-10 pt-4 w-full { padding ? 'pb-4' : '' }">
    <div class="flex items-center { whiteView ? 'text-zinc-900' : 'text-zinc-100' }">
        <a 
            href="/"
            class="logo text-3xl mr-8 cursor-pointer">
            whelp
        </a>
        {#if transparentView }
            <div class="flex bg-white pl-3 py-3 rounded-md relative w-[40vw] shadow-md">
                <input 
                    id="cflt-input"
                    type="text" 
                    class="bg-transparent outline-none border-r border-zinc-300 pr-2 w-[45%] text-zinc-900"
                    placeholder="pizza, pub, Fox & Hound"
                    value="{ cflt != null ? capitalize(cflt) : '' }"/>
                <input 
                    id="loc-input"
                    type="text" 
                    class="bg-transparent outline-none pl-2 w-[45%] mr-[12%] text-zinc-900"
                    placeholder="London"
                    value="{ capitalize(findLoc) }"/>
                <div 
                    id="search-btn"
                    class="absolute items-center flex justify-center right-[-1%] top-0 bg-red-600 w-14 h-full rounded-r-md cursor-pointer">
                    <i class="fa-solid fa-magnifying-glass text-2xl text-zinc-100"></i>
                </div>
            </div>
            <span
                x-data=""
                class="relative text-md cursor-pointer rounded-md py-2 px-3 hover:bg-zinc-400 hover:bg-opacity-30 ml-auto">
                Whelp for business
                <i class="fa-solid fa-chevron-down ml-1"></i>
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200" 
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-on:click.stop.outside="show = false"
                    class="absolute z-20 top-12 w-48 bg-zinc-100 right-0 py-3 px-3 rounded-lg text-zinc-900 border shadow-md">
                    <a 
                        class="flex items-center hover:bg-zinc-200 rounded p-1" 
                        href="{ user != null ? '/claim' : '/login'}">
                        <i class="fa-solid fa-store"></i>
                        <span class="ml-2">Add a Business</span>
                    </a>
                </div>
            </span>
            <a class="text-md ml-3 cursor-pointer rounded-md py-2 px-3 hover:bg-zinc-400 hover:bg-opacity-30 { user != null ? 'write-review-btn' : 'login-btn' }">
                Write a review
            </a>
            {#if user != null }
                <a
                    id="messages-btn" 
                    href="/messaging">
                    <i class="fa-regular fa-comment-dots text-2xl ml-3 cursor-pointer rounded-full py-2 px-2 hover:bg-zinc-400 hover:bg-opacity-30"></i>
                </a>
                <div 
                    id="notifications-btn"
                    x-data=""
                    class="relative">
                    <i class="fa-regular fa-bell text-2xl ml-3 cursor-pointer rounded-full py-2 px-2 hover:bg-zinc-400 hover:bg-opacity-30"></i>
                    {#if $user != null && $user.unreadNotifications.length > 0 }
                        <span
                            id="notifications-count" 
                            class="rounded-full bg-red-600 w-5 h-5 text-center absolute left-[65%] text-sm">
                            { $user.unreadNotifications.length }
                        </span>
                    {/if}
                    <div 
                        x-show="show"
                        x-transition:enter="transition ease-out duration-200" 
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-on:click.stop.outside="show = false"
                        class="absolute z-20 top-12 w-64 bg-zinc-100 right-0 py-3 px-3 rounded-lg text-zinc-900 border shadow-md">
                        {#if $user != null && $user.notifications.length > 0}
                            <!-- {% set notifications = app.user.notifications.toArray()|sort|reverse %} -->
                            {#each $user.notifications as notification }
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
                </div>
                <div
                    id="user-panel"
                    name="{ user.username }"
                    x-data=""
                    class="relative">
                    <img
                        class="rounded-full w-10 h-10 ml-4 cursor-pointer" 
                        src="build/images/avatar_default.19e0a8ff.jpg">
                    <div 
                        x-show="show"
                        x-transition:enter="transition ease-out duration-200" 
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-on:click.stop.outside="show = false"
                        class="absolute z-20 top-12 w-40 bg-zinc-100 right-0 py-3 px-3 rounded-lg text-zinc-900 border shadow-md">
                        <a 
                            class="flex items-center hover:bg-zinc-200 rounded p-1" 
                            href="/logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span class="ml-2">Log Out</span>
                        </a>
                    </div>
                </div>
            {:else}
                <button 
                    onclick="window.location.href='/login'"
                    class="py-2 px-4 border { whiteView ? 'border-zinc-400' : 'border-zinc-100' } rounded-md ml-3 hover:bg-zinc-400 hover:bg-opacity-30">
                    Log In
                </button>
                <button
                    onclick="window.location.href='/signup'"
                    class="py-2 px-4 bg-red-600 text-zinc-100 rounded-md ml-3">
                    Sign up
                </button>
            {/if}
        <div class="flex items-center text-sm { whiteView ? 'text-zinc-900' : 'text-zinc-100' }">
            <div 
                x-data="" 
                class="relative text-md cursor-pointer py-3 px-3 ml-[10vw] mr-2 border-b-4 border-transparent hover:border-red-600">
                Restaurants
                <i class="fa-solid fa-chevron-down ml-1"></i>
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200" 
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute z-20 w-[270px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-truck"></i>
                        Delivery
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-calendar-day"></i>
                        Reservations
                    </a>
                    <a
                        href="" 
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-burger"></i>
                        Burgers
                    </a>
                    <a
                        href="" 
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-fish"></i>
                        Japanese
                    </a>
                    <a
                        href="" 
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-bowl-food"></i>
                        Chinese
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-stroopwafel"></i>
                        Mexican
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-pizza-slice"></i>
                        Italian
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-shrimp"></i>
                        Thai
                    </a>
                </div>
            </div>
            <div 
                x-data="" 
                class="relative text-md cursor-pointer py-3 px-3 mr-2 border-b-4 border-transparent hover:border-red-600">
                Home Services
                <i class="fa-solid fa-chevron-down ml-1"></i>
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200" 
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute z-20 w-[300px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-hammer"></i>
                        Contractors
                    </a>
                    <a
                        href="" 
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-mountain-sun"></i>
                        Landscaping
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-plug"></i>
                        Electricians
                    </a>
                    <a
                        href="" 
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-unlock-keyhole"></i>
                        Locksmiths
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-broom"></i>
                        Home Cleaners
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-truck-fast"></i>
                        Movers
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-house-chimney-window"></i>
                        HVAC
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-faucet-drip"></i>
                        Plumbers
                    </a>
                </div>
            </div>
            <div 
                x-data="" 
                class="relative text-md cursor-pointer py-3 px-3 mr-2 border-b-4 border-transparent hover:border-red-600">
                Auto Services
                <i class="fa-solid fa-chevron-down ml-1"></i>
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200" 
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute z-20 w-[300px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-wrench"></i>
                        Auto Repair
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-car"></i>
                        Car Dealers
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-car-battery"></i>
                        Auto Detailing
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-oil-can"></i>
                        Oil Change
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-shop"></i>
                        Body Shops
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-square-parking"></i>
                        Parking
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-car-on"></i>
                        Car Wash
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        Towing
                    </a>
                </div>
            </div>
            <div 
                x-data="" 
                class="relative text-md cursor-pointer py-3 px-3 mr-2 border-b-4 border-transparent hover:border-red-600">
                More
                <i class="fa-solid fa-chevron-down ml-1"></i>
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200" 
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute z-20 w-[300px] bg-zinc-100 left-0 py-4 px-3 rounded-lg rounded-tl-none bottom-[-144px] grid grid-cols-2 text-zinc-900 h-[140px] border shadow-md">
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-socks"></i>
                        Dry Cleaning
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-spray-can-sparkles"></i>
                        Hair Salons
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                        Phone Repair
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-dumbbell"></i>
                        Gyms
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-beer-mug-empty"></i>
                        Bars
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-spa"></i>
                        Massage
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-martini-glass"></i>
                        Nightlife
                    </a>
                    <a 
                        href=""
                        class="rounded-md py-1 px-2 hover:bg-zinc-200">
                        <i class="fa-solid fa-bag-shopping"></i>
                        Shopping
                    </a>
                </div>
            </div>
        </div>
        {/if}
    </div>
</header>