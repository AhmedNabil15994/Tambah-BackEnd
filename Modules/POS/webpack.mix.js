const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'poss/js/pos.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'poss/css/pos.css');

if (mix.inProduction()) {
    mix.version();
}