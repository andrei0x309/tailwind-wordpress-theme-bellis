const mix = require('laravel-mix');
const concat = require('concat-files');
const path = require('path');
const purgecss = require('postcss-purgecss-laravel');
const tailwindcss = require('tailwindcss');
mix.setPublicPath('../a309');

let mainOptions = [ tailwindcss('./tailwind.js') , purgecss({content: [
            path.join(__dirname, "!(404).php"),
            path.join(__dirname, "parts/*.php"),
        ],})  ];
let error404Options = [ tailwindcss('./tailwind.js'), purgecss({content: [
            path.join(__dirname, "404.php"),
        ],}) ];
 
mix.sass('dev-assets/scss/app.scss', 'style.css', {} , mainOptions).options({processCssUrls: false}).version();
mix.sass('dev-assets/scss/app-amp.scss', 'amp-style.css', {} , mainOptions).options({processCssUrls: false}).version();
/* 
   .then(function () {
    /* concat([
        'dev-assets/css/template-header.css',
        'style.css'
    ], 'style.css', function (err) {
        if (err) { throw err; }
        console.log('done');
    }); */
//}); 


mix.js('dev-assets/js/app.js', 'js/app.js');
mix.js('dev-assets/js/app_index.js', 'js/app_index.js');
mix.js('dev-assets/js/app_comments.js', 'js/app_comments.js');

mix.copy('dev-assets/js/AMP/amp_base.js', 'js/AMP/amp_base.js');
mix.copy('dev-assets/js/AMP/amp_search_modal.js', 'js/AMP/amp_search_modal.js');
mix.copy('dev-assets/js/AMP/amp_comments.js', 'js/AMP/amp_comments.js');


mix.sass('dev-assets/scss/base/post-list.scss', 'css/post-list.css');
mix.sass('dev-assets/scss/search-results.scss', 'css/search-results.css');
mix.sass('dev-assets/scss/page-offline.scss', 'css/page-offline.css');
mix.sass('dev-assets/scss/404.scss', 'css/404.css', {} , error404Options).options({processCssUrls: false});
mix.sass('dev-assets/scss/single.scss', 'css/single.css').version();
mix.sass('dev-assets/scss/single-amp.scss', 'css/single-amp.css').version();



if (mix.inProduction()) {
    const files=[
    'js/app.js',
    'js/app_index.js',
    'js/app_comments.js',
    
    'js/AMP/amp_base.js',
    'js/AMP/amp_search_modal.js',
    'js/AMP/amp_comments.js',
    
    'style.css',
    'amp-style.css',
    
    'css/post-list.css',
    'css/search-results.css',
    'css/page-offline.css',
    'css/404.css',
    'css/single.css',
    'css/single-amp.css'
    ];
    
    mix.minify(files);
}