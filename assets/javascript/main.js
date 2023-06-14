try {
    var button = document.getElementById('notifications-btn');
    button.addEventListener('click', function() {
        fetch('/notifications/mark-as-read', {
            method: 'POST'
        }).then(function(response) {
            if (response.ok) {
                document.getElementById('notifications-count').remove();
            }
        })
    })
} catch (error) {}

try {
    window.onload = function () {
        var button = document.getElementsByClassName('add-friend-btn')[0];
        var firstName = document.getElementById('user-firstname').innerText.split(' ')[0];
        var currentUser = document.getElementById('current-user').innerText.split(' ')[0];

        button.addEventListener('click', function() {
            Swal.fire({
                html: `
                    <div class="text-left">
                        <div class="flex items-center text-red-600 font-bold text-2xl pb-4 border-b">
                            Add a friend
                            <i id="close-modal" class="fa-solid fa-xmark ml-auto cursor-pointer text-zinc-400"></i>
                        </div>
                        <div class="font-roboto-light text-left py-4 text-base">
                            Hi ${firstName}, <br>
                            <br>
                            I just added you to my list of friends on Whelp. Want to share reviews with me? <br>
                            <br>
                            - ${currentUser}
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
                        var friendId = document.getElementById('friend-id').innerText;
                        fetch('/users/add-friend', {
                            method: 'POST',
                            body: JSON.stringify({
                                id: Number(friendId)
                            })
                        }).then(function(response) {
                            if (response.ok) {
                                document.getElementById('friend-span').classList.remove('hidden');
                                button.remove();
                                Swal.close()
                            }
                        })
                    })
                }
              })
        });
    }
} catch (error) {}

try {
    window.onload = function () {
        var input = document.getElementById('message-input');
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
               sendMessage(input);
            }
        })

        var button = document.getElementById('send-message-btn');
        button.addEventListener('click', function() {
            sendMessage(input);
        })
    }
} catch (error) {}

function sendMessage(input) {
    var receiverId = document.getElementById('receiver-id').innerText;
    var lastSender = document.getElementById('last-sender').innerText;
    fetch('/messages/post', {
        method: 'POST',
        body: JSON.stringify({
            receiverId: Number(receiverId),
            content: input.value
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
                            <p class="font-roboto-light text-sm">${input.value}</p>
                        </div>
                    </div>
                `
                document.getElementById('messages-container').innerHTML += html;
                document.getElementById('last-sender').innerHTML = '1';
                input.value = '';
            })
        }
    })
}





