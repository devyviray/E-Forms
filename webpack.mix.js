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

// mix.js('resources/assets/js/app.js', 'public/js')
// .sass('resources/assets/sass/app.scss', 'public/css');
mix.styles([
	'resources/assets/css/bootstrap.min.css',
	'resources/assets/css/style.css',
	'resources/assets/css/light-bootstrap-dashboard.css',
	
], 'public/css/all.css')
.js([
	'resources/assets/js/app.js',
	'node_modules/popper.js/dist/popper.js',
	'node_modules/bootstrap/dist/js/bootstrap.min.js',
	'resources/assets/js/light-bootstrap-dashboard.js'
], 'public/js/all.js')
.browserSync('http://eforms.local');