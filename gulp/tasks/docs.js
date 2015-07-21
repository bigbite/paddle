module.exports = function (gulp, plugins, config) {
  var paths = config.paths.docs;

  gulp.task('docs', ['sassdoc'], function () {
    return gulp.src(paths.src)
      .pipe(plugins.webserver({
        livereload: true,
        port: 8082,
        open: true
      }));
  });
};
