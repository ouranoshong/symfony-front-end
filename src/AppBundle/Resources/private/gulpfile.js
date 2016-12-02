/**
 * Created by hong on 11/29/16.
 */


var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function() {
    gulp.src('sass/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('../public/css'));
});

gulp.task('watch:sass', function () {
    gulp.watch('sass/**/*.scss', ['sass']);
});

gulp.task('jquery', function() {
    gulp.src([
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/jquery/dist/jquery.slim.min.js'
    ]).pipe(gulp.dest('../public/js'));
});

gulp.task('bootstrap-js', function() {
    gulp.src([
        'bower_components/bootstrap/dist/js/bootstrap.min.js'
    ]).pipe(gulp.dest('../public/js'));
});

gulp.task('bootstrap-fonts', function() {
    gulp.src([
        'bower_components/bootstrap/dist/fonts/**'
    ]).pipe(gulp.dest('../public/fonts'));
});

gulp.task('bootstrap-css', function() {
    gulp.src([
        'bower_components/bootstrap/dist/css/*.min.css'
    ]).pipe(gulp.dest('../public/css'));
});

gulp.task('theme:yeti', function() {
    gulp.src([
        'yeti/*.min.css'
    ]).pipe(gulp.dest('../public/css'));
});

gulp.task('theme:journal', function() {
    gulp.src([
        'journal/*.min.css'
    ]).pipe(gulp.dest('../public/css'));
});

gulp.task('dist:bootstrap', ['bootstrap-js', 'bootstrap-fonts','bootstrap-css']);
gulp.task('dist', ['jquery', 'dist:bootstrap']);


gulp.task('images', function() {
    gulp.src('images/**/*')
        .pipe(gulp.dest('../public/images'));
});

gulp.task('han-css', function() {
   gulp.src('bower_components/Han/dist/han.min.css')
       .pipe(gulp.dest('../public/han'));
});

gulp.task('han-js', function() {
    gulp.src('bower_components/Han/dist/han.min.js')
        .pipe(gulp.dest('../public/han'));
})

gulp.task('han-font', function() {
    gulp.src('bower_components/Han/dist/font/*')
        .pipe(gulp.dest('../public/han/font'));
});

gulp.task('han', ['han-js', 'han-css', 'han-font']);
