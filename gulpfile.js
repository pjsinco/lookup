var elixir = require('laravel-elixir');
var gulp = require('gulp');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


gulp.task('copyfiles', function() {

    //gulp.src('vendor/bower_dl/typeahead.js/dist/typeahead.bundle.js' )
        //.pipe(gulp.dest('public/js'));

});

elixir(function(mix) {
    mix.scripts([
        'app.js',
    ], 'public/js/app.js');
    mix.sass('app.scss');
});
