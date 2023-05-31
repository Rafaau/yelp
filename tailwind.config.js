/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.{vue,js,ts,jsx,tsx}',
    './templates/**/*.html.twig',
  ],
  theme: {
    extend: {
      fontFamily: {
        'roboto': ['Roboto'],
        'roboto-thin': ['Roboto Thin'],
        'roboto-light': ['Roboto Light'],
        'roboto-regular': ['Roboto Regular'],
        'roboto-medium': ['Roboto Medium'],
        'roboto-black': ['Roboto Black'],
      },
      colors: {
        'zinc-150': '#EEEEEF',
      },
    },
  },
  plugins: [],
}

