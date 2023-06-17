<script lang='ts'>
    export let location: string;

    let mainCategories = {
        'Restaurants': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/8bc05b287157/assets/img/svg_illustrations/40x40_food_v2.svg',
        'Shopping': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/488aeb87ef6e/assets/img/svg_illustrations/40x40_gift_shops_v2.svg',
        'Nightlife': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/1397897c21a5/assets/img/svg_illustrations/40x40_new_v2.svg',
        'Sports & Leisure': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/0372f8e93aa9/assets/img/svg_illustrations/40x40_set_objective_v2.svg',
        'Beauty & Spas': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/5bd5d2648742/assets/img/svg_illustrations/40x40_barbers_v2.svg',
        'Car & Motorcycle': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/5967f38fe621/assets/img/svg_illustrations/40x40_auto_v2.svg',
        'Home Services': 'https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_design_cdn/7fc312973cf8/assets/img/svg_illustrations/40x40_home_services_v2.svg',
    }

    let show = false

    let moreCategories = {
        'Coffee & Tea': 'fa-solid fa-mug-hot mr-1',
        'Food': 'fa-solid fa-utensils mr-1',
        'Arts & Entertainment': 'fa-solid fa-palette mr-1',
        'Health & Medical': 'fa-solid fa-people-group mr-1',
        'Professional Services': 'fa-solid fa-shield-halved mr-1',
        'Pets': 'fa-solid fa-paw mr-1',
        'Property': 'fa-solid fa-house mr-1',
        'Hotel & Travel': 'fa-solid fa-hotel mr-1',
        'Local Services': 'fa-solid fa-map-location-dot mr-1',
        'Event Planning': 'fa-solid fa-calendar-day mr-1',
        'Government': 'fa-solid fa-building mr-1',
        'Financial Services': 'fa-solid fa-chart-line mr-1',
        'Education': 'fa-solid fa-graduation-cap mr-1',
        'Religious Organisations': 'fa-solid fa-hands-praying mr-1',
        'Local Flavour': 'fa-solid fa-seedling mr-1',
        'Mass Media': 'fa-solid fa-photo-film mr-1',
    }
</script>

<div class="max-w-[1300px] mx-auto">
    <div class="grid md:grid-cols-4 grid-cols-2 gap-8 px-10 pb-10">
        {#each Object.keys(mainCategories) as category}
            <a 
                href="{`search?cflt=${category}&find_loc=${location}`}"
                class="w-[100%] py-[6vw] text-center rounded border border-zinc-300 cursor-pointer hover:shadow-md">
                <img class="m-auto" src="{mainCategories[category]}" alt="404" />
                {category}
            </a>
        {/each}          
        <div 
            on:click={() => show = !show}
            on:keydown={null}
            class="w-[100%] py-[6vw] text-center rounded border border-zinc-300 cursor-pointer hover:shadow-md {show ? 'bg-zinc-200' : ''}">
            <img class="m-auto" src="https://s3-media0.fl.yelpcdn.com/assets/public/40x40_more_v2.yji-961fdce2fd036f85fb01.svg" alt="404" />
            More
        </div>  
    </div>
    {#if show}
        <div class="grid md:grid-cols-4 grid-cols-2 px-10 gap-8 mb-10 text-zinc-600">
            {#each Object.keys(moreCategories) as category}
                <a 
                    href="{`search?cflt=${category}&find_loc=${location}`}"
                    class="hover:underline md:ml-4 ml-8">
                    <i class="{moreCategories[category]}"></i>
                    {category}
                </a>
            {/each}
        </div>
    {/if}
</div>