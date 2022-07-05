let mix = require('laravel-mix');

mix.js('resources/assets/js/base.js', 'public/js');

// bootstrap4
mix.js('resources/assets/js/bootstrap.js', 'public/js')
    .autoload({
        "popper.js": ['Popper', 'window.Popper', 'popper', 'window.popper'],
        "jquery": ['$', 'window.jQuery']
    })
    .sass('resources/assets/sass/bootstrap.scss', 'public/css');

// coreui
mix.js('resources/assets/js/coreui.js', 'public/js')
    .sass('resources/assets/sass/coreui.scss', 'public/css');

//
mix.js('resources/assets/js/vue.js', 'public/js')
    .sass('resources/assets/sass/vue.scss', 'public/css');


mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/zipcode.js', 'public/js');

//バージョニング
if (mix.inProduction()) {
   mix.version();
}