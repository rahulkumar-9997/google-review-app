const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/frontend/css', [
       require('tailwindcss'),
   ]);