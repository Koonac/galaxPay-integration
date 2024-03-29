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

mix
    .sass('public/scss/myStyle.scss', 'public/css/myBootstrap.css')

    .scripts('node_modules/jquery/dist/jquery.js', 'public/js/jquery.js')
    .scripts('node_modules/jquery-mask-plugin/dist/jquery.mask.js', 'public/js/jqueryMask.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.bundle.js', 'public/js/bootstrap.js')
// .js('resources/js/app.js', 'public/js')
// .postCss('resources/css/app.css', 'public/css', [
//         //
//     ]);