const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/index.js', 'public/js').vue()
    .js('resources/js/show.js', 'public/js').vue()
    .js('resources/js/print-caliper-comp.js', 'public/js').vue()
    .sass('resources/css/main.scss', 'public/css')
    .css('resources/css/multiselect.css', 'public/css')
    .sass('resources/css/circular-bar/circle.scss', 'public/css')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]);
