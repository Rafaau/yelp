<script lang='ts'>
    import { currentUser } from "../../store";

    async function getConversations() {
        const res = await fetch(`/messages/conversations`);
        const data = await res.json();

        if (res.ok) {
            console.log(data)
            return data.conversations;
        } else {
            throw new Error(data.message);
        }
    }

    let promise = getConversations();
</script>

{#await promise then conversations}
    <div class="relative top-16 border-t">
        {#if $currentUser.messagesReceived.length || $currentUser.messagesSent.length}
            <div class="w-1/3 mx-auto mt-6">
                {#each conversations as conversation}
                    <a href={`/messaging/${$currentUser.id}-${$currentUser.id == conversation.sender.id ? conversation.receiver.id : conversation.sender.id}`}>
                    <div class="border rounded p-4 mb-3 w-full cursor-pointer hover:shadow-md">
                        <div class="flex items-center">
                            <img src="/build/images/avatar_default.19e0a8ff.jpg" class="w-16 h-16 rounded-full border">
                            <div class="font-semibold text-2xl ml-4">
                                {#if conversation.sender.id != $currentUser.id}
                                    {conversation.sender.username}                    
                                {:else}
                                    {conversation.receiver.username}
                                {/if}
                                <div class="text-base font-normal">
                                    {conversation.sender.id != $currentUser.id ? conversation.sender.username : 'You'}:
                                    <span class="font-roboto-light text-sm ml-1">
                                        {conversation.content}
                                    </span>
                                </div>   
                            </div>
                        </div>
                    </div>
                    </a>
                {/each}
            </div>
        {:else}
            <div class="text-center mt-[15vh]">
                <img src="https://s3-media0.fl.yelpcdn.com/assets/public/thank_you_327x200_v2.yji-d4271fb447609986c493.svg" class="w-1/4 mx-auto">
                <p class="font-bold text-3xl">No messages at this time.</p>
                <p class="font-semibold text-2xl">When someone sends you a message, it will appear here.</p>
            </div>
        {/if}
    </div>
{/await}