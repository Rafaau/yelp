<script lang='ts'>
    function onSubmit(e) {
        const formData = new FormData(e.target) as any

        const data = {}
        for (let field of formData) {
            const [key, value] = field
            data[key] = value
        }

        let isValid = 
            data['terms'] == 'on' &&
            data['first-name'] != '' &&
            data['last-name'] != '' &&
            data['email'] != '' &&
            data['password'] != '';
        
        if (isValid) {
            fetch('/users/register', {
                method: 'POST',
                body: JSON.stringify({
                    firstName: data['first-name'],
                    lastName: data['last-name'],
                    email: data['email'],
                    password: data['password'],
                })
            }).then(function(response) {
                if (response.ok) {
                    window.location.href = '/login'
                }
            });
        }
    }
</script>

<div 
    class="w-80">
    <p class="text-center text-red-600 font-semibold text-xl">
        Sign Up for Whelp
    </p>
    <p class="text-center text-sm font-semibold mt-1">
        Connect with great local businesses
    </p>
    <div class="mt-2">
        <form on:submit|preventDefault={onSubmit}>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="terms">
                <label class="text-xs text-gray-500">
                    By continuing, I agree to Yelp’s <a class="text-blue-500 hover:underline" href="https://www.yelp.co.uk/static?p=tos">Terms of Service</a> and acknowledge Yelp’s <a class="text-blue-500 hover:underline" href="https://www.yelp.com/tos/privacy_policy">Privacy Policy</a>, including Yelp’s cookie policy.
                </label>
            </div>
            <div class="flex space-x-2 mb-2 mt-4">
                <input 
                    name="first-name"
                    class="border border-zinc-400 px-2 py-1 w-[50%] rounded font-roboto-light outline-none mb-2"
                    placeholder="First Name">
                <input
                    name="last-name"
                    class="border border-zinc-400 px-2 py-1 w-[50%] rounded font-roboto-light outline-none mb-2"
                    placeholder="Last Name">
            </div>
            <input
                name="email" 
                class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2"
                placeholder="Email">
            <input
                name="password" 
                class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2"
                placeholder="Password"
                type="password">
            <input type="text" class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2" placeholder="ZIP Code">
            <p class="font-semibold">
                Birthday
                <span class="text-xs text-gray-400 font-roboto-light ml-1">
                    Optional
                </span>
            </p>
            <div class="flex my-2 space-x-2">
                <select class="border border-zinc-400 px-2 py-1 w-[33%] rounded font-roboto-light outline-none mb-2">
                    <option value="Month">Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>           
                </select>
                <select class="border border-zinc-400 px-2 py-1 w-[33%] rounded font-roboto-light outline-none mb-2">
                    <option value="Day">Day</option>
                    {#each Array(31) as i}
                        <option value="{{ i }}">{{ i }}</option>
                    {/each}
                </select>
                <select class="border border-zinc-400 px-2 py-1 w-[33%] rounded font-roboto-light outline-none mb-2">
                    <option value="Year">Year</option>
                    {#each Array.from({length: 124}, (_, i) => 2023 - i) as year}
                        <option value={year}>{year}</option>
                    {/each}
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox">
                <label class="text-xs text-gray-500">
                    Yes, I want Whelp to send me marketing emails about Yelp’s products, services, and local events. I can unsubscribe at any time.
                </label>
            </div>
            <button
                type="submit"
                class="py-2 px-4 bg-red-600 text-zinc-100 font-semibold rounded w-full mt-4">
                Sign up
            </button>
            <p class="pt-4 mt-4 border-t text-center text-xs text-zinc-400">
                Already on Yelp? 
                <a 
                    class="text-blue-500 hover:underline cursor-pointer" 
                    href="/login">
                    Log in
                </a>
            </p>
        </form>
    </div>
</div>