var gulp = require("gulp");
var sourcemaps = require("gulp-sourcemaps");
var babel = require("gulp-babel");
var concat = require("gulp-concat");
var uglify = require("gulp-uglify");
var browserify = require("browserify");
var glob = require('glob');
var source = require('vinyl-source-stream');
var rename = require('gulp-rename');
var babelify = require('babelify');
var cleanCSS = require('gulp-clean-css');

gulp.task('default', function() {
    myEntries = glob.sync('js/@(classes|controllers)**/srm*.js');
    myEntries.push('js/ready.js');

    browserify({
        entries: myEntries,
        extensions: ['.js'],
        debug: true
    })
        .transform(babelify)
        .bundle()
        .pipe(source('dist.js'))
        .pipe(gulp.dest('./dist'));

    var vendorEntries = [
        'vendor/jquery/jquery.min.js',
        'vendor/js-cookie/js-cookie.js',
        'vendor/bootstrap/js/bootstrap.min.js',
        'vendor/owl-carousel/owl.carousel.min.js',
        'vendor/bxslider/plugins/jquery.fitvids.js',
        'vendor/bxslider/plugins/jquery.easing.1.3.js',
        'vendor/bxslider/jquery.bxslider.min.js'
    ];

    gulp.src(vendorEntries)
        .pipe(concat('vendor-dist.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./dist'));


    var css = [
        'vendor/font-awesome/css/font-awesome.min.css',
        'vendor/bootstrap/css/bootstrap.min.css',
        'vendor/bootstrap/css/bootstrap-theme.min.css',
        'vendor/owl-carousel/owl.carousel.css',
        'vendor/owl-carousel/owl.theme.css',
        'vendor/bxslider/jquery.bxslider.css',
        'css/absconsultor.css',
        'css/masonry.css',
        'node_modules/viewerjs/dist/viewer.min.css'
        //'css/home.css'
    ];

    gulp.src(css)
        .pipe(concat('css-dist.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./dist/css'));

    gulp.src(['vendor/font-awesome/fonts/**'])
    .pipe(gulp.dest('./dist/fonts'));

    gulp.src([
        '../img/flags/flags-sprite.png',
        '../img/footer-background-logo.png'
    ]).pipe(gulp.dest('./dist/img'));

    gulp.src([
        'vendor/bxslider/images/**'
    ]).pipe(gulp.dest('./dist/css/images'));

    gulp.src([
        'vendor/owl-carousel/grabbing.png',
        'vendor/owl-carousel/AjaxLoader.gif'
    ]).pipe(gulp.dest('./dist/css'));


});