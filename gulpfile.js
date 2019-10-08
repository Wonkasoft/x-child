let gulp = require('gulp'),
sass = require('gulp-sass'),
sourcemaps = require('gulp-sourcemaps'),
concat = require('gulp-concat'),
path = require('path'),
jshint = require('gulp-jshint'),
jsmin = require('gulp-js-minify'),
cleanCSS = require('gulp-clean-css'),
plumber = require('gulp-plumber'),
notify = require('gulp-notify'),
browserSync = require('browser-sync').create(),
json = require('json-file'),
themeName = json.read('./package.json').get('name'),
siteName = json.read('./package.json').get('siteName'),
local = json.read('./package.json').get('localhost'),
themeDir = '../' + themeName,
plumberErrorHandler = { errorHandler: notify.onError({

	title: 'Gulp',

	message: 'Error: <%= error.message %>',

	line: 'Line: <%= line %>'

})

};
sass.compiler = require('node-sass');

// Static server
gulp.task('browser-sync', function() {
	browserSync.init({
		proxy: local + siteName,
		https: true,
		port: 4000
	});
});

gulp.task('sass', function () {

	return gulp.src('./sass/style.scss')

	.pipe(sourcemaps.init())

	.pipe(plumber(plumberErrorHandler))

	.pipe(sass())

	.pipe(cleanCSS())

	.pipe(concat('style.css'))

	.pipe(sourcemaps.write('./maps'))

	.pipe(gulp.dest('./'))

	.pipe(browserSync.stream());

});

gulp.task('woo-sass', function () {

	return gulp.src('./sass/woocommerce.scss')

	.pipe(sourcemaps.init())

	.pipe(plumber(plumberErrorHandler))

	.pipe(sass())

	.pipe(cleanCSS())

	.pipe(concat('woocommerce.css'))

	.pipe(sourcemaps.write('./maps'))

	.pipe(gulp.dest('./'))

	.pipe(browserSync.stream())

	.pipe(notify({
		message: "✔︎ CSS task complete",
		onLast: true
	}));

});

gulp.task('js', function () {

	return gulp.src( './js/wonkamizer-js.js' )

	.pipe(concat(themeName + '.min.js'))

	.pipe(plumber(plumberErrorHandler))

	.pipe(jshint())

	.pipe(jshint.reporter('default'))

	.pipe(jshint.reporter('fail'))

	.pipe(jsmin())
	
	.pipe(sourcemaps.write('./maps'))

	.pipe(gulp.dest('./assets/js'))

	.pipe(browserSync.stream())

	.pipe(notify({ message: "✔︎ JS task complete"}));

});

gulp.task('watch', function() {

	gulp.watch('**/sass/**/*.scss', gulp.series(gulp.parallel('sass', 'woo-sass'))).on('change', browserSync.reload);
	gulp.watch('**/*.php').on('change', browserSync.reload);
	gulp.watch('./js/*.js', gulp.series(gulp.parallel('js'))).on('change', browserSync.reload);

});

gulp.task('default', gulp.series(gulp.parallel('sass', 'woo-sass', 'js', 'watch', 'browser-sync')));