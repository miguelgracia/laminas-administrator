var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify');

gulp.task('build', function () {
    gulp.src([
        'node_modules/capture-video-frame/capture-video-frame.js',
        'js/app.js',
        'js/routing.js',
        'js/classes/*.js',
        'js/controllers/*.js',
        'js/ready.js'
    ])
        .pipe(concat('administrator.min.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('js/build/'))
});