const sveltePreprocess = require('svelte-preprocess');

module.exports = {
    preprocess: sveltePreprocess({ 
        sourceMap: !process.env.production,
        defaults: {
            script: "typescript",
        },
        postcss: {
            plugins: [
                require('tailwindcss'),
                require('autoprefixer'),
            ],
        },
    }),
};
