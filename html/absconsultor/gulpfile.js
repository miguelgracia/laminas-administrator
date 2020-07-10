var gulp = require("gulp");
var sourcemaps = require("gulp-sourcemaps");
var concat = require("gulp-concat");
var uglify = require("gulp-uglify");
var browserify = require("browserify");
var glob = require('glob');
var source = require('vinyl-source-stream');
var babelify = require('babelify');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var buffer = require('vinyl-buffer');
var log = require('gulplog');


function buildSass() {
    return (gulp.src('./sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./css')));
}

function moveCss() {
    const css = [
        'css/animate.css',
        'css/icomoon.css',
        'css/simple-line-icons.css',
        'css/style.css',
    ];

    return (gulp.src(css)
        .pipe(concat('css-dist.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./dist/css')));
}

function moveImages() {
    return (gulp.src([
        '../img/flags/flags-sprite.png',
        '../img/footer-background-logo.png'
    ]).pipe(gulp.dest('./dist/img')));
}

function vendor() {
    const vendorEntries = [
        'node_modules/bootstrap.native/dist/bootstrap-native.js',
        'node_modules/js-cookie/src/js.cookie.js',
        'node_modules/waypoints/lib/noframework.waypoints.js'
    ];

    return (gulp.src(vendorEntries)
        .pipe(concat('vendor-dist.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('./dist')));
}

function buildJs() {
    let myEntries = glob.sync('js/@(classes|controllers)**/srm*.js');
    myEntries.push('js/ready.js');

    return browserify({
        entries: myEntries,
        extensions: ['.js'],
        debug: false
    })
        .transform(babelify)
        .bundle()
        .pipe(source('dist.js'))
        .pipe(buffer())
        .pipe(sourcemaps.init({loadMaps: true}))
        // Add transformation tasks to the pipeline here.
        //.pipe(uglify())
        .on('error', log.error)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./dist'));
}

const css = gulp.series(buildSass, moveCss, moveImages);
const js = gulp.series(vendor, buildJs);

const watch = gulp.parallel(function watcher() {
    gulp.watch('./sass/**/*.scss', gulp.series(css));
    gulp.watch('./js/**/*.js', gulp.series(js));
});

exports.sass = buildSass;
exports.moveCss = moveCss;
exports.moveImages = moveImages;
exports.vendor = vendor;
exports.buildJs = buildJs;
exports.css = css;
exports.js = js;
exports.watch = watch;