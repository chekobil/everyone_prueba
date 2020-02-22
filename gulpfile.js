var gulp = require('gulp');
var notify = require('gulp-notify');
var stylus = require('gulp-stylus');
var concatcss = require('gulp-concat-css');
var livereload = require('gulp-livereload');

gulp.task('stylus', function (done) {
    return gulp.src(['./app/stylus/estilos.styl'])
      .pipe(stylus())
      .pipe(concatcss("./app/css/estilos.css"))
      .pipe(gulp.dest('./'))
      .pipe(livereload());
      done()
});

gulp.task('html', function (done) {
    return gulp.src('./app/*.html')
      .pipe(livereload());
      done()
});

gulp.task('watch', function (done) {
	livereload.listen();
    gulp.watch('./app/stylus/*.styl', gulp.series('stylus'));
    gulp.watch('./app/*.html', gulp.series('html'));
    done();
});

gulp.task('default', gulp.series('watch'));