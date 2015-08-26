'use strict';

var elixir = require('laravel-elixir'),
    source = require('vinyl-source-stream'),
    buffer = require('vinyl-buffer'),
    gulp = require('gulp'),
    browserify = require('browserify'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    maps = require('gulp-sourcemaps');


gulp.task('copyfiles', function() {

    //gulp.src('vendor/bower_dl/typeahead.js/dist/typeahead.bundle.js' )
        //.pipe(gulp.dest('public/js'));

});

gulp.task('js', function() {

    var b = browserify({
        entries: 'resources/assets/js/app.js',
        debug: true
    });

    return b.bundle()
        .pipe(source('app.js'))
        .pipe(buffer())
        .pipe(maps.init({loadMaps: true}))
            .pipe(rename('public/js/app.js'))
            .on('error', console.log)
        .pipe(maps.write('./'))
        .pipe(gulp.dest('./'));


    //gulp.src('resources/assets/js/**/*.js')
        //.pipe(browserify({debug: true}))
        //.pipe(gulp.dest('./'));

});

gulp.task('sass', function() {

    gulp.src('resources/assets/sass/**/*.scss')    
        .pipe(maps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(maps.write())
        .pipe(rename('public/css/app.css'))
        .pipe(gulp.dest('./'))

});

gulp.task('watch', function() {

    gulp.watch('resources/assets/sass/**/*.scss', ['sass']);
    gulp.watch('resources/assets/js/**/*.js', ['js']);

});


//elixir(function(mix) {
//    //mix.scripts([
//        //'app.js',
//    //], 'public/js/app.js');
//    mix.sass('app.scss');
//});
