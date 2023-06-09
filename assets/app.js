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

new Globals({
    target: document.getElementById('globals-target')
})

new Header({
    target: document.getElementById('header-target')
})

new Footer({
    target: document.getElementById('footer-target')
})

new Homepage({
    target: document.getElementById('homepage-target')
})



