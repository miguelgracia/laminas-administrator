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
        .pipe(gulp.dest('./dist'))
});