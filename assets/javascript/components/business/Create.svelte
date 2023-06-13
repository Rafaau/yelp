<script lang='ts'>
    let view = {'name': 1, 'location': 2, 'website': 3, 'category': 4, 'hours': 5};
    let currentView = view.name;

</script>

<div class="relative top-16 flex h-full justify-center">
    <form>
    {#if currentView == view.name}
        <div 
            class="w-[45vw] pt-16 pb-8 px-16">
            <p class="text-3xl font-black">Hello! Let’s start with your business name</p>
            <p class="text-gray-500 font-roboto-light mt-4">
                We’ll use this information to help you claim your Whelp page. Your business will come up automatically if it is already listed.
            </p>
            <input 
                class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
                type="text"
                placeholder="Your business name">
            <button
                @click="view = 'location'"
                type="button" 
                class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
                Continue
            </button>
        </div>
    {:else if currentView == view.location}
    <div class="w-[45vw] py-8 px-16">
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
            placeholder="Your business location">
        <input 
            class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
            type="text"
            placeholder="Your business address">
        <button
            on:click={() => currentView = view.website}
            type="button" 
            class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
            Continue
        </button>
    </div>
    {:else if currentView == view.website}
    <div class="w-[45vw] py-8 px-16">
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
            placeholder="Website">
        <input 
            class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
            type="text"
            placeholder="Phone number">
        <button
            @click="view = 'category'"
            type="button" 
            class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
            Continue
        </button>
    </div>
    <div 
        x-show="view == 'category'" 
        class="w-[45vw] py-8 px-16">
        <p
            class="text-cyan-700 mb-2 cursor-pointer" 
            @click="view = 'website'">
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
            id="category-input"
            value="">
        <input 
            class="outline-none border rounded mt-8 font-roboto-light py-3 px-4 border-zinc-400 w-full"
            type="text"
            placeholder="Your business description"> 
        <button
            @click="view = 'hours'"
            type="button" 
            class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
            Continue
        </button>
    </div>
    <div 
        x-show="view == 'hours'" 
        class="w-[45vw] py-8 px-16">
        <p
            class="text-cyan-700 mb-2 cursor-pointer" 
            @click="view = 'category'">
            <i class="fa-solid fa-arrow-left text-xs mr-2"></i>
            Back
        </p>
        <p class="text-3xl font-black">What are your business hours?</p>
        <p class="text-gray-500 font-roboto-light my-4">
            Help customers find your service at the right time.
        </p>
            {% set days = { 1: 'Monday', 2: 'Tuesday', 3: 'Wednesday', 4: 'Thursday', 5: 'Friday', 6: 'Saturday', 7: 'Sunday' } %}
            {% for day in days %}
                <div class="flex items-center my-2 space-x-2 justify-start">
                    <label class="w-24">{{ day }}</label>
                    <select 
                        id="{{ 'select-open-' ~loop.index }}" 
                        class="border rounded py-1 px-3 border-zinc-400">
                        <option>Closed</option>
                        {% for i in 0..23 %}
                            <option>{{ i == 0 ? '0:00 AM' : (i <= 12 ? i ~ ':00 AM' : (i == 12 ? i ~ ':00 PM' : (i - 12) ~ ':00 PM')) }}</option>
                        {% endfor %}
                    </select>
                    <select
                        id="{{ 'select-close-' ~loop.index }}" 
                        class="border rounded py-1 px-3 border-zinc-400 hidden">
                        {% for i in 0..23 %}
                            <option>{{ i == 0 ? '12:00 AM' : (i <= 12 ? i ~ ':00 AM' : (i == 12 ? i ~ ':00 PM' : (i - 12) ~ ':00 PM')) }}</option>
                        {% endfor %}
                    </select>
                </div>
            {% endfor %}
            {{ form_widget(businessForm._hours, {
                'attr': {
                'class': 'hidden',
                'value': '0:00 AM - 0:00 AM, 0:00 AM - 0:00 AM, 0:00 AM - 0:00 AM, 0:00 AM - 0:00 AM, 0:00 AM - 0:00 AM, 0:00 AM - 0:00 AM, 0:00 AM - 0:00 AM'
            }
            }) }}
        <button
            id="create-biz-btn" 
            type="submit"
            class="bg-red-600 rounded py-2 px-4 text-zinc-100 font-semibold mt-6">
            Create my business page
        </button>
    </div> 
    {{ form_end(businessForm) }}       
    <div class="relative w-[45vw] py-8 px-16">
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