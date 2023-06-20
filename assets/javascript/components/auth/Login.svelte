<script lang='ts'>
    interface MyWindow extends Window {
        APP_DATA: {
            csrfToken: string;
        };
    }
    const myWindow = window as unknown as MyWindow;
    let csrfToken = myWindow.APP_DATA.csrfToken;

    let email: string;
    let password: string;
    let error: boolean = false;

    async function handleSubmit() {
        const response = await fetch("/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify({
                email: email,
                password: password,
            }),
        });

        if (response.ok) {
            window.location.href = "/";
        } else {
            error = true;
        }
    }
</script>

<div 
    class="w-80">
    <p class="text-center text-red-600 font-semibold text-xl">
        Log in to Whelp
    </p>
    <p class="text-center text-sm font-semibold mt-1">
        New to Whelp? 
        <a 
            class="text-blue-500 hover:underline cursor-pointer" 
            href="/signup">
            Sign up
        </a>
    </p>
    <form method="post" on:submit|preventDefault={handleSubmit} class="mt-2">
        <div class="flex items-center space-x-2">
            <input type="checkbox" required>
            <label class="text-xs text-gray-500">
                By continuing, I agree to Yelp’s <a class="text-blue-500 hover:underline" href="https://www.yelp.co.uk/static?p=tos">Terms of Service</a> and acknowledge Yelp’s <a class="text-blue-500 hover:underline" href="https://www.yelp.com/tos/privacy_policy">Privacy Policy</a>, including Yelp’s cookie policy.
            </label>
        </div>
        <input 
            type="text" 
            class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none my-2 form-control" 
            placeholder="Email"
            name="email" 
            id="inputEmail" 
            autocomplete="email"
            bind:value={email} 
            required 
            autofocus>
        <input 
            type="password" 
            class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2 form-control" 
            placeholder="Password"
            name="password" 
            id="inputPassword"
            bind:value={password}  
            autocomplete="current-password" 
            required>
        <input type="hidden" name="_csrf_token" bind:value={csrfToken} />
        <p class="text-red-600 text-sm {error ? 'block' : 'hidden'}"><strong>Email</strong> or <strong>Password</strong> is invalid</p>
        <button
            type="submit"
            class="py-2 px-4 bg-red-600 text-zinc-100 font-semibold rounded w-full mt-4">
            Log In
        </button>
        <p class="pt-4 mt-4 border-t text-right text-xs text-zinc-400">
            New to Whelp? 
            <a 
                class="text-blue-500 hover:underline cursor-pointer" 
                href="/signup">
                Sign up
            </a>
        </p>
    </form>
</div>