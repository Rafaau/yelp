<script lang='ts'>
    let firstName: string;
    let lastName: string;
    let email: string;
    let password: string;

    function validateEmail(e) {
        const email = e.target;
        if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            email.setCustomValidity('Please enter a valid email address.');
        } else {
            email.setCustomValidity('');
        }
    }

    function validatePassword(e) {
        const password = e.target;

        if (!password.value.match(/^[a-zA-Z0-9]{8,}$/)) {
            password.setCustomValidity('Password must be at least 8 characters long');
        } else {
            password.setCustomValidity('');
        }
    }

    function validateFirstName(e) {
        const firstName = e.target;

        if (!firstName.value.match(/^[a-zA-Z]+$/)) {
            firstName.setCustomValidity('First name must only contain letters');
        } else if (!firstName.value.match(/^[a-zA-Z]{2,}$/)) {
            firstName.setCustomValidity('First name must be at least 2 characters long');
        } else if (!firstName.value.match(/^[A-Z].*/)) {
            firstName.setCustomValidity('First name must start with a capital letter');
        } else {
            firstName.setCustomValidity('');
        }
    }

    function validateLastName(e) {
        const lastName = e.target;

        if (!lastName.value.match(/^[a-zA-Z]+$/)) {
            lastName.setCustomValidity('Last name must only contain letters');
        } else if (!lastName.value.match(/^[a-zA-Z]{2,}$/)) {
            lastName.setCustomValidity('Last name must be at least 2 characters long');
        } else if (!lastName.value.match(/^[A-Z].*/)) {
            lastName.setCustomValidity('Last name must start with a capital letter');
        } else {
            lastName.setCustomValidity('');
        }
    }

    function onSubmit(e) {
        fetch('/users/register', {
            method: 'POST',
            body: JSON.stringify({
                firstName,
                lastName,
                email,
                password,
            })
        }).then(function(response) {
            if (response.ok) {
                window.location.href = '/login'
            }
        });
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
                <input type="checkbox" name="terms" required>
                <label class="text-xs text-gray-500">
                    By continuing, I agree to Yelp’s <a class="text-blue-500 hover:underline" href="https://www.yelp.co.uk/static?p=tos">Terms of Service</a> and acknowledge Yelp’s <a class="text-blue-500 hover:underline" href="https://www.yelp.com/tos/privacy_policy">Privacy Policy</a>, including Yelp’s cookie policy.
                </label>
            </div>
            <div class="flex space-x-2 mb-2 mt-4">
                <div>
                    <input 
                        name="first-name"
                        bind:value={firstName}
                        on:input={validateFirstName}
                        class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2"
                        placeholder="First Name"
                        required
                        >
                </div>
                <div>
                    <input
                        name="last-name"
                        bind:value={lastName}
                        on:input={validateLastName}
                        class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2"
                        placeholder="Last Name"
                        required>
                </div>
            </div>
            <input
                name="email" 
                bind:value={email}
                on:input={validateEmail}
                class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2"
                placeholder="Email"
                required>
            <input
                name="password" 
                bind:value={password}
                on:input={validatePassword}
                class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2"
                placeholder="Password"
                type="password"
                required>
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