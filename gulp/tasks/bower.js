module.exports = function (gulp, plugins, config) {
  var paths = config.paths.bower;

  gulp.task('bower', function () {
    return gulp.src(plugins.bower())
      .pipe(plugins.concat('components.js'))
      .pipe(plugins.uglify())
      .pipe(gulp.dest(paths.out));
  });
};
