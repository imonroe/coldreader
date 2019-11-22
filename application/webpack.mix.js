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

mix.sass('resources/assets/sass/app.scss', 'public/css')
   .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
   .copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js/bootstrap.min.js')
   .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js/sweetalert.min.js')
   .copy('node_modules/sweetalert/dist/sweetalert.css', 'public/css/sweetalert.css')
   .copy('node_modules/tinymce/skins', 'public/css/tinymce_skins')
   .copy('node_modules/tinymce', 'public/js/tinymce')
   .copy('node_modules/jquery-ui-dist/jquery-ui.min.js', 'public/js/jquery-ui.min.js')
   .copy('node_modules/jquery-ui-dist/jquery-ui.min.css', 'public/css/jquery-ui.min.css')
   .copy('node_modules/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js', 'public/js/jquery.ui.touch-punch.min.js')
   .copy('resources/assets/css/basic_theme.css', 'public/css/basic_theme.css')
   .js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/bootstrap.js', 'public/js')
   .js('resources/assets/js/coldreader.js', 'public/js')
   .webpackConfig({
        resolve: {
            modules: [
                'node_modules'
            ],
            alias: {
                'vue$': 'vue/dist/vue.js',
                'jquery-ui': 'jquery-ui-dist/jquery-ui.js'
            }
        }
   });