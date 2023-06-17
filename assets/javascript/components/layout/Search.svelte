<script lang='ts'>
    import { redirect } from '../../libs/utils';

    export let cflt = '';
    export let findLocInput = '';
    export let className = '';
</script>

<div class="flex bg-white pl-3 py-3 rounded-md relative w-[40vw] shadow-md {className}">
    <input 
        id="cflt-input"
        type="text" 
        class="bg-transparent outline-none border-r border-zinc-300 pr-2 w-[45%] text-zinc-900"
        placeholder="pizza, pub, Fox & Hound"
        bind:value={cflt}/>
    <input 
        id="loc-input"
        type="text" 
        class="bg-transparent outline-none pl-2 w-[45%] mr-[12%] text-zinc-900"
        placeholder="London"
        bind:value={findLocInput}/>               
    <div   
        on:click={() => redirect(`/search?cflt=${cflt ? cflt : ''}&find_loc=${findLocInput}`)}
        on:keydown={null}
        class="absolute items-center flex justify-center right-[-1%] top-0 bg-red-600 w-14 h-full rounded-r-md cursor-pointer">
        <i class="fa-solid fa-magnifying-glass text-2xl text-zinc-100"></i>
    </div>
</div>