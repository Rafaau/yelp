<script lang='ts'>
    import OpenHours from "./OpenHours.svelte";

    let view = {'name': 1, 'location': 2, 'website': 3, 'category': 4, 'hours': 5};
    let viewError = {'name': false, 'location': false, 'website': false, 'category': false, 'hours': false};
    let currentView = view.name;

    let name: string = '';
    let location: string = '';
    let address: string = '';
    let website: string = '';
    let phone: string = '';
    let categories: string = '';
    let description: string = '';
    let hours: string = '12:00 AM - 12:00 AM,12:00 AM - 12:00 AM,12:00 AM - 12:00 AM,12:00 AM - 12:00 AM,12:00 AM - 12:00 AM,12:00 AM - 12:00 AM,12:00 AM - 12:00 AM';

    function validateNameView() {
        if (!name.length) {
            viewError.name = true
        } else {
            viewError.name = false
            currentView = view.location
        }
    }

    function validateLocationView() {
        if (!location.length || !address.length) {
            viewError.location = true
        } else {
            viewError.location = false
            currentView = view.website
        }
    }

    function validateCategoryView() {
        if (!categories.length || !description.length) {
            viewError.category = true
        } else {
            viewError.category = false
            currentView = view.hours
        }
    }

    function setCategories() {
        if (!website.length || !phone.length) {
            viewError.website = true
            return
        }

        viewError.website = false
        currentView = view.category;

        setTimeout(() => {
            let select = document.getElementById('category-select') as any;
            select.onchange = function () {
                var selected = document.querySelector('select[id="category-select"] option:checked') as any;
                var container = document.getElementById('category-list');
                var newCategory = document.createElement('div');
                newCategory.setAttribute('class', 'flex items-center bg-cyan-700 bg-opacity-[0.25] text-cyan-700 font-bold px-2 py-1 rounded-sm mr-2 mt-2 w-auto');
                newCategory.innerHTML = select.value;
                var closeIcon = document.createElement('i');
                closeIcon.setAttribute('class', 'fa-solid fa-xmark ml-2 cursor-pointer ml-auto');
                var hiddenInput = document.getElementById('category-input') as any;              
                hiddenInput.value += `${selected.parentElement.label},${select.value},`;
                categories = hiddenInput.value;
                closeIcon.addEventListener('click', function() {
                    newCategory.remove();
                    hiddenInput.value = hiddenInput.value.replace(select.value + ',', '');
                });
                newCategory.appendChild(closeIcon);
                container.appendChild(newCategory);
            }
        }, 0)
    }

    function onSubmit(e) {
        hours = (document.getElementById('hours-input') as any).value;

        fetch('/businesses/create', {
            method: 'POST',
            body: JSON.stringify({
                name,
                location,
                address,
                website,
                phone,
                categories,
                description,
                hours
            })
        }).then(function(response) {
            if (response.ok) {
                window.location.href = `biz?name=${encodeURIComponent(name)}&loc=${location}`;
            } else {
                alert('Something went wrong');
            }           
        })
    }

</script>

<div class="relative top-16 flex h-full justify-center">
    <form on:submit|preventDefault={onSubmit}>
        {#if currentView == view.name}
            <div 
                class="md:w-[45vw] w-full pt-16 pb-8 px-16">
                <p class="text-3xl font-black">Hello! Let’s start with your business name</p>
                <p class="text-gray-500 font-roboto-light mt-4">
                    We’ll use this information to help you claim your Whelp page. Your business will come up automatically if it is already listed.
                </p>
                <input 
                    class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                    type="text"
                    name="name"
                    bind:value={name}
                    placeholder="Your business name">
                <p class="text-red-600 text-sm {viewError.name ? 'block' : 'hidden'}">*Missing form fields</p>
                <button
                    on:click={validateNameView}
                    type="button" 
                    class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
                    Continue
                </button>
            </div>
        {:else if currentView == view.location}
            <div class="md:w-[45vw] w-full py-8 px-16">
                <p
                    class="text-cyan-700 mb-2 cursor-pointer" 
                    on:click={() => currentView = view.name}>
                    <i class="fa-solid fa-arrow-left text-xs mr-2"></i>
                    Back
                </p>
                <p class="text-3xl font-black">What’s your business location?</p>
                <p class="text-gray-500 font-roboto-light mt-4">
                    Add the location and address of Mario's Plumber to help customers find you.
                </p>
                <input 
                    class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                    type="text"
                    name="location"
                    bind:value={location}
                    placeholder="Your business location">
                <input 
                    class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                    type="text"
                    name="address"
                    bind:value={address}
                    placeholder="Your business address">
                <p class="text-red-600 text-sm {viewError.location ? 'block' : 'hidden'}">*Missing form fields</p>
                <button
                    on:click={validateLocationView}
                    type="button" 
                    class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
                    Continue
                </button>
            </div>
        {:else if currentView == view.website}
            <div class="md:w-[45vw] w-full py-8 px-16">
                <p
                    class="text-cyan-700 mb-2 cursor-pointer" 
                    on:click={() => currentView = view.location}>
                    <i class="fa-solid fa-arrow-left text-xs mr-2"></i>
                    Back
                </p>
                <p class="text-3xl font-black">Do you have a business website?</p>
                <p class="text-gray-500 font-roboto-light mt-4">
                    Tell your customers where they can find more information about your business.
                </p>
                <input 
                    class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                    type="text"
                    name="website"
                    bind:value={website}
                    placeholder="Website">
                <input 
                    class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                    type="text"
                    name="phone"
                    bind:value={phone}
                    placeholder="Phone number">
                <p class="text-red-600 text-sm {viewError.website ? 'block' : 'hidden'}">*Missing form fields</p>
                <button
                    on:click={setCategories}
                    type="button" 
                    class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
                    Continue
                </button>
            </div>
        {:else if currentView == view.category}
            <div class="md:w-[45vw] w-full py-8 px-16">
                <p
                    class="text-cyan-700 mb-2 cursor-pointer" 
                    on:click={() => currentView = view.website}>
                    <i class="fa-solid fa-arrow-left text-xs mr-2"></i>
                    Back
                </p>
                <p class="text-3xl font-black">What kind of business are you in?</p>
                <p class="text-gray-500 font-roboto-light mt-4">
                    Help customers find your product and service. You can add up to 3 categories that best describe what <strong>Mario's Plumber</strong>’s core business is. You can always edit and add more later.
                </p>
                <select
                    id="category-select"
                    class='outline-none border rounded mt-4 font-roboto-light py-3 px-4 border-zinc-400 w-full'>
                    <option selected disabled>Choose a category</option>
                    <optgroup label = "Restaurants">
                        <option value="Pizza">Pizza</option>
                        <option value="Burgers">Burgers</option>
                        <option value="Mexican">Mexican</option>
                        <option value="Chinese">Chinese</option>
                        <option value="Italian">Italian</option>
                        <option value="Japanese">Japanese</option>
                        <option value="Thai">Thai</option>
                        <option value="Fast Food">Fast Food</option>
                        <option value="Coffee & Tea">Coffee & Tea</option>
                        <option value="Bakeries">Bakeries</option>
                        <option value="Delivery">Delivery</option>
                        <option value="Takeout">Takeout</option>
                        <option value="Reservations">Reservations</option>
                    </optgroup>
                    <optgroup label = "Home Services">
                        <option value="Contractors">Contractors</option>
                        <option value="Electricians">Electricians</option>
                        <option value="Home Cleaners">Home Cleaners</option>
                        <option value="Landscaping">Landscaping</option>
                        <option value="Locksmiths">Locksmiths</option>
                        <option value="Movers">Movers</option>
                        <option value="Plumbers">Plumbers</option>
                        <option value="HVAC">HVAC</option>
                        <optgroup label = "Auto Services">
                        <option value="Auto Repair">Auto Repair</option>
                        <option value="Body Shops">Body Shops</option>
                        <option value="Auto Detailing">Auto Detailing</option>
                        <option value="Oil Change">Oil Change</option>
                        <option value="Car Dealers">Car Dealers</option>
                        <option value="Towing">Towing</option>
                        <option value="Parking">Parking</option>
                        <option value="Car Wash">Car Wash</option>
                    </optgroup>
                    <optgroup label = "More">
                        <option value="Dry Cleaning">Dry Cleaning</option>
                        <option value="Hair Salons">Hair Salons</option>
                        <option value="Phone Repair">Phone Repair</option>
                        <option value="Gyms">Gyms</option>
                        <option value="Bars">Bars</option>
                        <option value="Massage">Massage</option>
                        <option value="Nightlife">Nightlife</option>
                        <option value="Shopping">Shopping</option>
                    </optgroup>
                </select>
                <div 
                    id="category-list"
                    class="grid grid-cols-2">
                </div>
                <input 
                    class="hidden"
                    type="text"
                    name="categories"
                    bind:value={categories}
                    id="category-input">
                <input 
                    class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                    type="text"
                    name="description"
                    bind:value={description}
                    placeholder="Your business description"> 
                <p class="text-red-600 text-sm {viewError.category ? 'block' : 'hidden'}">*Missing form fields</p>
                <button
                    on:click={validateCategoryView}
                    type="button" 
                    class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
                    Continue
                </button>
            </div>
        {:else if currentView == view.hours}
            <div class="md:w-[45vw] w-full py-8 px-16">
                <p
                    class="text-cyan-700 mb-2 cursor-pointer" 
                    on:click={() => currentView = view.category}>
                    <i class="fa-solid fa-arrow-left text-xs mr-2"></i>
                    Back
                </p>
                <p class="text-3xl font-black">What are your business hours?</p>
                <p class="text-gray-500 font-roboto-light my-4">
                    Help customers find your service at the right time.
                </p>
                <OpenHours/>
                <input 
                    class="hidden"
                    type="text"
                    name="hours"
                    id="hours-input"
                    bind:value={hours}/>
                <button
                    id="create-biz-btn" 
                    type="submit"
                    class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
                    Create my business page
                </button>
            </div> 
        {/if}
    </form>   
    <div class="relative w-[45vw] py-8 px-16 md:block hidden">
        <img 
            src="https://s3-media0.fl.yelpcdn.com/assets/public/cityscape_300x233_v2.yji-deccc3d10e15b4494be1.svg" 
            alt="404"
            class="absolute left-[5vw] top-[5vw] w-[25vw]">
        <img 
            src="https://s3-media0.fl.yelpcdn.com/assets/public/searching_on_map_234x177_v2.yji-0b5da3ce1e6a636298be.svg" 
            alt="404"
            class="absolute left-[15vw] top-[15vw] w-[20vw]">   
    </div>
</div>