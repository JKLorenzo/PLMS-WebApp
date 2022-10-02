const mix = require("laravel-mix");

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

mix.sass("resources/sass/bootstrap.scss", "public/css")
    .js("resources/js/app.js", "public/js")
    .copy("resources/css", "public/css")
    .copy("resources/css/config", "public/css/config")
    .copy("resources/fonts", "public/fonts")
    .copy(
        "resources/fonts/vendor/bootstrap-icons",
        "public/fonts/vendor/bootstrap-icons"
    )
    .copy("resources/images", "public/images")
    .copy(
        "resources/js/{*.min.js,units.js,button-theme-settings.js,incidents.js,vendor.*}",
        "public/js"
    )
    .copy("resources/js/pages", "public/js/pages")
    .copy("resources/libs", "public/libs")
    .version()
    .sourceMaps();
