<script lang='ts'>
    interface MyWindow extends Window {
        APP_DATA: {
            csrfToken: string;
        };
    }
    const myWindow = window as unknown as MyWindow;
    let csrfToken = myWindow.APP_DATA.csrfToken;
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
    <form method="post" class="mt-2">
        <div class="flex items-center space-x-2">
            <input type="checkbox">
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
            required 
            autofocus>
        <input 
            type="password" 
            class="border border-zinc-400 px-2 py-1 w-[100%] rounded font-roboto-light outline-none mb-2 form-control" 
            placeholder="Password"
            name="password" 
            id="inputPassword"  
            autocomplete="current-password" 
            required>
        <input type="hidden" name="_csrf_token" bind:value={csrfToken} />
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