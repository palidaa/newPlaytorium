let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/timesheet.js', 'public/js')
   .js('resources/assets/js/new.js', 'public/js')
   .js('resources/assets/js/project.js', 'public/js')
   .js('resources/assets/js/holiday.js', 'public/js')
   .js('resources/assets/js/report.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
