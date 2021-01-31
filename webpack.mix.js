const mix = require('laravel-mix');
const concat = require('concat-files');
require('laravel-mix-tailwind');
mix.setPublicPath('../a309');
require('laravel-mix-purgecss');


//mix.js('css', 'public/js');
   mix.sass('dev-assets/scss/app.scss', 'style.css').tailwind().version()
  /* .purgeCss({
        enabled: true,

        // Your custom globs are merged with the default globs. If you need to
        // fully replace the globs, use the underlying `paths` option instead.
        globs: [
            path.join(__dirname, "*.php"),
        ],

        extensions: ['php', 'scss'],

        // Other options are passed through to Purgecss
       
        //whitelist: ['random', 'yep', 'button'],
        
        //whitelistPatterns: [/language/, /hljs/],
       
        //whitelistPatternsChildren: [/^markdown$/],
    }) 
*/
   .then(function () {
    concat([
        'dev-assets/css/template-header.css',
        'style.css'
    ], 'style.css', function (err) {
        if (err) { throw err; }
        console.log('done');
    }); 
}); 

mix.js('dev-assets/js/app_index.js', 'js/app_index.js');
mix.js('dev-assets/js/app_comments.js', 'js/app_comments.js');

mix.sass('dev-assets/scss/comments.scss', 'css/comments.css').version();
        
        /*    
        
mix.webpackConfig({
  plugins: [
    new PurgecssPlugin({
      paths: glob.sync([
        path.join(__dirname, "*.php", '!node_modules/*', '!assets/*', '!images/*', '!nbproject/*', '!.git/*'),
      ]),
      extractors: [
        {
          extractor: TailwindExtractor,
          extensions: ["html", "js", "php", "vue"]
        }
      ]
    })
  ]
});  */