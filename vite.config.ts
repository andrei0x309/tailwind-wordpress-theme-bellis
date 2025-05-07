import fs from 'node:fs'
import { defineConfig, createLogger } from 'vite';
import path from 'path';
import tailwindcss from '@tailwindcss/vite'

const bannerText = fs.readFileSync(path.resolve(__dirname, 'dev-assets/css/template-header.css'), 'utf-8');

const logger = createLogger();
const originalWarning = logger.warn;
logger.warn = (msg, options) => {
  if (msg.includes('build.outDir') && msg.includes('source files')) return;
  if (msg.includes('fonts/icomoon') && msg.includes('resolve')) return;
  originalWarning(msg, options);
};

export default defineConfig({
  base: './',
  customLogger: logger,
  plugins: [
    tailwindcss(),
  ],
  build: {
    // The output directory relative to the project root (theme root)
    // Mix's setPublicPath('./') generally means the output goes into the theme's root directory.
    outDir: './assets',
    emptyOutDir: true,
    manifest: true,

    // Configure Rollup for production builds
    rollupOptions: {
      input: {
        // Define each JS file that needs to be a separate output bundle as an entry point
        'js/app': path.resolve(__dirname, 'dev-assets/js/app.js'),
        'js/app_index': path.resolve(__dirname, 'dev-assets/js/app_index.js'),
        'js/app_comments': path.resolve(__dirname, 'dev-assets/js/app_comments.js'),

        // Define each SCSS file that needs to be a separate CSS output as an entry point
        // Vite will handle compilation and output .css files (and tiny .js entry files)
        'css/tailwind': path.resolve(__dirname, 'dev-assets/css/tailwind.css'),
        'css/style': path.resolve(__dirname, 'dev-assets/scss/app.scss'), // Outputs style.css and style.<hash>.css
        'css/post-list': path.resolve(__dirname, 'dev-assets/scss/base/post-list.scss'), // Outputs css/post-list.css and css/post-list.<hash>.css
        'css/search-results': path.resolve(__dirname, 'dev-assets/scss/search-results.scss'), // etc.
        'css/page-offline': path.resolve(__dirname, 'dev-assets/scss/page-offline.scss'),
        'css/404': path.resolve(__dirname, 'dev-assets/scss/404.scss'),
        'css/single': path.resolve(__dirname, 'dev-assets/scss/single.scss'),

        // If 'dev-assets/css/template-header.css' needs to be a separate entry point
        // 'template-header': path.resolve(__dirname, 'dev-assets/css/template-header.css'),
        // But ideally, @import its content into dev-assets/scss/app.scss if possible.
      },
      output: {
         // Customize output filenames. Vite adds hashes for versioning by default in production.
         // This replicates Mix's .version() behavior.
         // Example: to remove the default 'assets/' subdirectory in output filenames:
         assetFileNames: '[name].[hash][extname]', // For CSS, fonts, images etc.
         chunkFileNames: '[name].[hash].js', // For code-split JS chunks
         entryFileNames: '[name].[hash].js', // For your JS entry points

         // If you need specific output names *without* hashes for some files (like style.css),
         // this requires more complex Rollup output configuration or a post-build renaming step.
         // Vite's automatic versioning is generally preferred for cache busting.
      },
    },
    

    // // Vite automatically minifies JS and CSS in production builds
    // // You don't need a separate mix.minify() step.
    // minify: 'terser', // Or 'esbuild' for faster JS minification (esbuild does CSS too)
  },

  // Development server options (optional, for hot reloading during development)
  // server: {
  //   port: 3000, // Or any port
  //   strictPort: true,
  //   // Configure proxy if WordPress is running on a different port
  //   // proxy: {
  //   //   '/': { target: 'http://localhost:80', changeOrigin: true }
  //   // }
  // }
});