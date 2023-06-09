tippy('#messages-btn', {
    content: 'Messages',
});

tippy('#notifications-btn', {
    content: 'Notifications',
    zIndex: 9,
});

try {
    var username = document.getElementById('user-panel').getAttribute('name');
    tippy('#user-panel', {
        content: username,
        zIndex: 9,
    });
} catch (error) {}

tippy('#star-1', {
    content: 'Not good',
});

tippy('#star-2', {
    content: "Could've been better",
});

tippy('#star-3', {
    content: 'OK',
});

tippy('#star-4', {
    content: 'Good',
});

tippy('#star-5', {
    content: 'Great',
});

tippy('#claimed-info', {
    content: 'This business has been claimed by the owner or a representative.'
});

tippy('#unclaimed-info', {
    content: 'This business has not yet been claimed by the owner or a representative.'
});
