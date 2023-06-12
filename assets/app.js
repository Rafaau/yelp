/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './javascript/main.js';
import './javascript/tooltips.js';
import './javascript/endpoint-calls.js';
import Globals from './javascript/components/Globals.svelte';
import Header from './javascript/components/layout/Header.svelte';
import Footer from './javascript/components/layout/Footer.svelte';
import Homepage from './javascript/components/home/Homepage.svelte';
import Browse from './javascript/components/search/Browse.svelte';
import UserDetails from './javascript/components/user/UserDetails.svelte';
import Business from './javascript/components/business/Business.svelte';

new Globals({
    target: document.getElementById('globals-target')
})

new Header({
    target: document.getElementById('header-target')
})

new Footer({
    target: document.getElementById('footer-target')
})

if (document.getElementById('browse-target')) {
    new Browse({
        target: document.getElementById('browse-target')
    })
} else if (document.getElementById('homepage-target')) {
    new Homepage({
        target: document.getElementById('homepage-target')
    })
} else if (document.getElementById('user-details-target')) {
    new UserDetails({
        target: document.getElementById('user-details-target')
    })
} else if (document.getElementById('business-target')) {
    new Business({
        target: document.getElementById('business-target')
    })
}



