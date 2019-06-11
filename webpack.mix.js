const mix = require('laravel-mix');
const concat = require('concat-files');
require('laravel-mix-tailwind');
mix.setPublicPath('../a309');

//mix.js('css', 'public/js');
   mix.sass('assets/scss/app.scss', 'style.css').tailwind().version()
   .then(function () {
    concat([
        'assets/css/template-header.css',
        'style.css'
    ], 'style.css', function (err) {
        if (err) { throw err; }
        console.log('done');
    });
}); 
        