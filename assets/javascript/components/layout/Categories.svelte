<script lang="ts">
    export let label: string;
    export let findLoc: string;
    
    let restaurantsCategories = {
        'Delivery': 'fa-solid fa-truck',
        'Reservations': 'fa-solid fa-calendar-day',
        'Burgers': 'fa-solid fa-burger',
        'Japanese': 'fa-solid fa-fish',
        'Chinese': 'fa-solid fa-bowl-food',
        'Mexican': 'fa-solid fa-stroopwafel',
        'Italian': 'fa-solid fa-pizza-slice',
        'Thai': 'fa-solid fa-shrimp',
    }

    let homeServicesCategories = {
        'Contractors': 'fa-solid fa-hammer',
        'Landscaping': 'fa-solid fa-mountain-sun',
        'Electricians': 'fa-solid fa-plug',
        'Locksmiths': 'fa-solid fa-unlock-keyhole',
        'Home Cleaners': 'fa-solid fa-broom',
        'Movers': 'fa-solid fa-truck-fast',
        'HVAC': 'fa-solid fa-house-chimney-window',
        'Plumbers': 'fa-solid fa-faucet-drip',
    }

    let autoServicesCategories = {
        'Auto Repair': 'fa-solid fa-wrench',
        'Car Dealers': 'fa-solid fa-car',
        'Auto Detailing': 'fa-solid fa-car-battery',
        'Oil Change': 'fa-solid fa-oil-can',
        'Body Shops': 'fa-solid fa-shop',
        'Parking': 'fa-solid fa-square-parking',
        'Car Wash': 'fa-solid fa-car-on',
        'Towing': 'fa-solid fa-truck-ramp-box',
    }

    let moreCategories = {
        'Dry Cleaning': 'fa-solid fa-socks',
        'Hair Salons': 'fa-solid fa-spray-can-sparkles',
        'Phone Repair': 'fa-solid fa-mobile-screen-button',
        'Gyms': 'fa-solid fa-dumbbell',
        'Bars': 'fa-solid fa-beer-mug-empty',
        'Massage': 'fa-solid fa-spa',
        'Nightlife': 'fa-solid fa-martini-glass',
        'Shopping': 'fa-solid fa-bag-shopping',
    }

    let activeCategories = label == 'Restaurants' ? 
        restaurantsCategories : 
        label == "Home Services" ?
        homeServicesCategories :
        label == "Auto Services" ?
        autoServicesCategories :
        moreCategories;
</script>

{#each Object.keys(activeCategories) as category}
    <a 
        href={`/search?cflt=${category}&find_loc=${findLoc}`}
        class="rounded-md py-1 px-2 hover:bg-zinc-200">
        <i class="{activeCategories[category]}"></i>
        {category}
    </a>
{/each}
