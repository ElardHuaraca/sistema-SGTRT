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
    .sass('resources/css/app.scss', 'public/css', [
    ]);

mix.js('resources/js/navigation.js', 'public/js')
mix.js('resources/js/home.js', 'public/js')
mix.js('resources/js/report/resource_it.js', 'public/js/report')
mix.js('resources/js/user/user-manage.js', 'public/js/user')
mix.js('resources/js/maintenance/project.js', 'public/js/maintenance')
mix.js('resources/js/maintenance/cost.js', 'public/js/maintenance')
mix.js('resources/js/maintenance/sow.js', 'public/js/maintenance')

mix.postCss('resources/css/auth.css', 'public/css');
mix.postCss('resources/css/navigation.css', 'public/css');
mix.postCss('resources/css/home.css', 'public/css');
mix.postCss('resources/css/user-manage.css', 'public/css');

mix.copyDirectory('resources/images', 'public/images');
