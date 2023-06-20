<script lang='ts'>
    import { currentUser } from "../../store";

    let currentURL = window.location.href;
    let lastOctet = currentURL.split('/').pop();
    let senderId = lastOctet.split('-')[0];
    let receiverId = lastOctet.split('-')[1];

    async function getMessages() {    
        const res = await fetch(`/messages?senderId=${senderId}&receiverId=${receiverId}`);
        const data = await res.json();

        if (res.ok) {
            console.log(data)
            return data;
        } else {
            throw new Error(data.message);
        }
    }

    let promise = getMessages();

    let receiver;

    promise.then(data => {
        receiver = data.receiver;
    })

    let input = '';

    function postMessage() {
        if (input == '') return
        var lastSender = Number(document.getElementById('last-sender').innerHTML);
        fetch('/messages/post', {
            method: 'POST',
            body: JSON.stringify({
                receiverId: Number(receiverId),
                content: input
            })
        }).then(function(response) {
            if (response.ok) {
                response.json().then(data => {
                    var today = new Date().toLocaleDateString('en-US', { month: 'long', day: '2-digit' });
                    var html = `
                        <div class="flex space-x-3">
                            ${lastSender == 1 ? '<div class="w-12"></div>' : 
                                '<img src="/build/images/avatar_default.19e0a8ff.jpg" class="rounded-full w-10 h-10 border mt-3">'
                            }               
                            <div class="w-full">                   
                                ${lastSender == 1 ? '' : `
                                    <p class="font-semibold text-sm flex items-center mt-3">
                                        ${data.sender}
                                        <span class="ml-auto text-xs font-roboto-light text-zinc-500">${today}</span>
                                    </p>
                                `}
                                <p class="font-roboto-light text-sm">${input}</p>
                            </div>
                        </div>
                    `
                    document.getElementById('messages-container').innerHTML += html;
                    document.getElementById('last-sender').innerHTML = '1';
                    input = '';
                })
            }
        })
    }
</script>

{#await promise then data}
    <div class="relative border-t h-screen pt-20">
        <div class="lg:w-1/3 md:w-1/2 w-full md:px-0 px-2 mx-auto border-b pb-4">
            <div class="flex items-center space-x-4">
                <img src={`/${receiver.userImage}`} class="rounded-full w-16 h-16 border">
                <p class="font-semibold text-2xl">{receiver.username}</p>
            </div>
        </div>
        <div class="overflow-y-scroll lg:w-1/3 md:w-1/2 w-full mx-auto h-[69vh] hide-scrollbar py-4 px-3 pb-16">
            <p class="font-roboto-light text-sm mb-2">
                This is the beginning of your message history with <strong>{receiver.username}</strong>
            </p>
            <div id="messages-container">
                {#each data.messages as message, i}
                    <div class="flex space-x-3">
                        {#if i == 0 || i == 2 && data.messages[i - 2].sender.id != message.sender.id}
                            <img src={`/${message.sender.userImage}`} class="rounded-full w-10 h-10 border mt-3">
                        {:else}
                            <div class="w-12"></div>
                        {/if}
                        <div class="w-full">
                            {#if i == 0 || i == 2 && data.messages[i - 2].sender.id != message.sender.id}
                                <div class="font-semibold text-sm flex items-center mt-3">
                                    {message.sender.username}
                                    <span class="ml-auto text-xs font-roboto-light text-zinc-500">{new Date(message.postDate.date).toLocaleDateString('en-GB', {day: 'numeric', month: 'long'})
                                    }</span>
                                </div>
                            {/if}
                            <p class="font-roboto-light text-sm">{message.content}</p>
                        </div>
                    </div>
                {/each}
            </div>
        </div>
        <div class="lg:w-1/3 md:w-1/2 w-full mx-auto absolute bottom-0 pb-1 lg:left-1/3 md:left-1/4 md:px-0 px-2 left-0 bg-zinc-100">
            <div id="receiver-id" class="hidden">{receiver.id}</div>
            <div id="last-sender" class="hidden">{data.messages.length > 0 && data.messages[data.messages.length - 1].sender.id == $currentUser.id ? 1 : 0}</div>
            <div class="border-zinc-300 border rounded flex items-center">
                <input
                    id="message-input" 
                    class="outline-none px-4 py-2 w-[90%] bg-transparent" 
                    type="text" 
                    placeholder="Type a message"
                    bind:value={input}
                    on:keydown={(e) => e.key == 'Enter' && postMessage()}>
                <i
                    on:click={postMessage}
                    on:keydown={null} 
                    id="send-message-btn" 
                    class="fa-solid fa-paper-plane ml-auto text-red-600 text-xl px-3 pt-1 cursor-pointer">
                </i>
            </div>
        </div>
    </div>
{/await}