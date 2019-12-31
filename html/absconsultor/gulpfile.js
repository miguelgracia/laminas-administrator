var gulp = require("gulp");
var sourcemaps = require("gulp-sourcemaps");
var babel = require("gulp-babel");
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

gulp.task('sass', async function () {
    return gulp.src('./sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./css'));
});

gulp.task('default', async function() {
    let myEntries = glob.sync('js/@(classes|controllers)**/srm*.js');
    myEntries.push('js/ready.js');

    browserify({
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


    var vendorEntries = [
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/jquery.easing/jquery.easing.min.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'node_modules/waypoints/lib/jquery.waypoints.min.js',
        'node_modules/js-cookie/src/js.cookie.js',
    ];

    gulp.src(vendorEntries)
        .pipe(concat('vendor-dist.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./dist'));

    var css = [
        'css/animate.css',
        'css/icomoon.css',
        'css/simple-line-icons.css',
        'css/style.css',
    ];

    gulp.src(css)
        .pipe(concat('css-dist.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./dist/css'));

    gulp.src([
        '../img/flags/flags-sprite.png',
        '../img/footer-background-logo.png'
    ]).pipe(gulp.dest('./dist/img'));

});