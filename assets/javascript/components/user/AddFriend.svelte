<script lang='ts'>
    import Swal from 'sweetalert2';
    import { currentUser } from "../../store";

    export let user;

    function openModal() {
        Swal.fire({
                html: `
                    <div class="text-left">
                        <div class="flex items-center text-red-600 font-bold text-2xl pb-4 border-b">
                            Add a friend
                            <i id="close-modal" class="fa-solid fa-xmark ml-auto cursor-pointer text-zinc-400"></i>
                        </div>
                        <div class="font-roboto-light text-left py-4 text-base">
                            Hi ${user.username.split(' ')[0]}, <br>
                            <br>
                            I just added you to my list of friends on Whelp. Want to share reviews with me? <br>
                            <br>
                            - ${$currentUser.username}
                        </div>
                        <button id="send-friend-request" class="font-semibold text-zinc-100 bg-red-600 rounded px-3 py-2 outline-none">
                            Send
                        </button>
                    </div>
                `,
                showConfirmButton: false,
                didOpen: () => {
                    const content = Swal.getHtmlContainer()
                    const close = content.querySelector('#close-modal')
                    close.addEventListener('click', () => {
                        Swal.close()
                    })
                    const send = content.querySelector('#send-friend-request')
                    send.addEventListener('click', () => {
                        fetch('/users/add-friend', {
                            method: 'POST',
                            body: JSON.stringify({
                                id: user.id
                            })
                        }).then(function(response) {
                            if (response.ok) {
                                document.getElementById('friend-span').classList.remove('hidden');
                                document.getElementById('add-friend-btn').remove();
                                Swal.close()
                            }
                        })
                    })
                }
              })
    }
</script>

<a 
    on:click={openModal}
    id="add-friend-btn"
    class="mb-1 flex items-center cursor-pointer {currentUser != null ? 'add-friend-btn' : 'login-btn'}">
    <i class="fa-solid fa-user-plus text-xs mr-2"></i>
    <span class="hover:underline">Add Friend</span>
</a>