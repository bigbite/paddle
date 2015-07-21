var gulp = require('gulp');
require('./gulp/boot');

/**
 * Default task.
 */
gulp.task('default', ['styles', 'webpack:build', 'static', 'images', 'svg2png']);

/**
 * Build task. Cleans and runs default task.
 */
gulp.task('build', ['clean'], function () {
  gulp.start('default');
});

/**
 * Server task. To be used by the server for building.
 */
gulp.task('server-build', ['clean'], function () {
  gulp.start(['styles', 'webpack:build', 'static']);
});
