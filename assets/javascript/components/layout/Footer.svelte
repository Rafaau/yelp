<script lang='ts'>
    let currentURL = new URL(window.location.href);
    let hide = currentURL.pathname.includes('claim') || currentURL.pathname.includes('messaging');

    function scrollToTop() {
        document.getElementsByClassName('logo')[0].scrollIntoView({behavior: "smooth"});
    }

    let aboutList = [
        'About Whelp',
        'Careers',
        'Press',
        'Investor Relations',
        'Trust & Safety',
        'Content Guidelines',
        'UK Gender Pay Gap Report',
        'Terms of Service',
        'Privacy Policy',
        'Ad Choices',
        'Manage Cookies',
    ]

    let discoverList = [
        'Collections',
        'Talk',
        'Events',
        'Whelp Blog',
        'Support',
        'Whelp Mobile',
        'Developers',
        'RSS',
    ]

    let whelpForBusinessList = [
        'Whelp for Business',
        'Business Owner Login',
        'Claim your Business Page',
        'Advertise on Whelp',
        'Business Success Stories',
        'Business Support',
        'Whelp Blog for Business',
    ]

</script>

{#if !hide}
<div class="bg-zinc-200 px-10 py-10">
    <div class="grid md:grid-cols-4 grid-cols-2 max-w-[1200px] mx-auto md:ml-0 ml-8">
        <div class="[&>button]:block [&>button]:text-zinc-500 [&>button]:py-[0.125rem]">
            <p class="font-bold pb-4">About</p>
            {#each aboutList as about}
                <button on:click={scrollToTop} class="hover:underline">{about}</button>
            {/each}
        </div>
        <div class="[&>button]:block [&>button]:text-zinc-500 [&>button]:py-[0.125rem]">
            <p class="font-bold pb-4">Discover</p>
            {#each discoverList as discover}
                <button on:click={scrollToTop} class="hover:underline">{discover}</button>
            {/each}
        </div>
        <div class="[&>button]:block [&>button]:text-zinc-500 [&>button]:py-[0.125rem] md:mt-0 mt-8">
            <p class="font-bold pb-4">Whelp for Business</p>
            {#each whelpForBusinessList as whelpForBusiness}
                <button on:click={scrollToTop} class="hover:underline">{whelpForBusiness}</button>
            {/each}
        </div>      
        <div class="[&>button]:block [&>button]:text-zinc-500 [&>button]:py-[0.125rem]">
            <p class="font-bold pb-1">Languages</p>
            <button class="hover:underline">English</button>
            <p class="font-bold pb-1 pt-3">Countries</p>
            <button class="hover:underline">United Kingdom</button>
        </div>          
    </div>
</div>
{/if}