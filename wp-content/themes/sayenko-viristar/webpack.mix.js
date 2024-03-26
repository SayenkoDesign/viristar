const mix = require('laravel-mix');
require('laravel-mix-polyfill');
require('laravel-mix-copy-watched');
require('laravel-mix-imagemin');

require('postcss');

const glob = require("glob");
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Sage application. By default, we are compiling the Sass file
 | for your application, as well as bundling up your JS files.
 |
 */

 mix.options({
    // Don't perform any css url rewriting by default
    processCssUrls: true,
	postCss: [
        require('css-has-pseudo')
    ]
})


//mix.setPublicPath('./dist');
mix.setPublicPath('./');
mix.setResourceRoot('..');

mix.browserSync({
	proxy: 'https://viristar.local',
	port: 3000,
	notify: false,
	open: true,
	files: [
		'front-page.php',
		'archive*.php',
		'single*.php',
		'blocks/**/*.php',
		'page-templates/**/*.php',
		'template-parts/**/*.php',
		'dist/scripts/**/*.js',
		'dist/styles/**/*.css'
	],
});


mix.webpackConfig({
	stats: {
        children: true,
    },
	devtool: mix.inProduction() ? false : 'source-map',
	performance: { hints: false },
	externals: { jquery: 'jQuery' },
});

mix.autoload({
	jquery: ['$', 'window.jQuery'],
});

mix.js('assets/scripts/project.js', 'dist/scripts')
	.sass('assets/styles/style.scss', 'dist/styles')
	.sass( 'assets/styles/editor-style.scss', 'dist/styles' )
	.options({ processCssUrls: true })
	.copyWatched('assets/images/**', 'dist/images', { base: 'assets/images' })

;

// GSAP
// mix.js('assets/scripts/gsap.js', 'dist/scripts');

// FancyBox
/* mix.js('assets/scripts/fancybox.js', 'dist/scripts')
	.sass('assets/styles/fancybox.scss', 'dist/styles'); */

// PLYR - for videos
/* mix.js('assets/scripts/plyr.js', 'dist/scripts')
	.sass('assets/styles/plyr.scss', 'dist/styles'); */

// FacetWP add infoBox
/* mix.scripts('assets/scripts/infobox.js', 'dist/scripts/infobox.js');
 */


// Splide
mix.copy('node_modules/@splidejs/splide/dist/css/splide-core.min.css', 'dist/splide')
.copy('node_modules/@splidejs/splide/dist/js/splide.min.js', 'dist/splide');

/*mix.js('assets/scripts/slick.js', 'dist/scripts')
	.sass('assets/styles/slick.scss', 'dist/styles'); */


// Blocks - CSS
glob.sync('blocks/**/src/block.scss').map(function(file) { 
	mix.sass(file, `${path.join(path.dirname(file), '../')}style.css`).options({
		processCssUrls: false
	});
});

// Blocks - JS
glob.sync('blocks/**/src/block.js').map(function(file) { 
	mix.js(file, `${path.join(path.dirname(file), '../')}dist/block.js`); 
});

// Blocks - Images
glob.sync('blocks/**/src/images').map(function(folder) { 
	mix.copyWatched(folder + '/**', `${path.join(folder, '../../')}dist/images`) 
});

// mix.extract();

mix.sourceMaps(! mix.inProduction());