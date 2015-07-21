module.exports = function (gulp, plugins, config) {
  var paths = config.paths.images;

  gulp.task('svg2png', function () {
    if (config.ENV === 'production') {
      plugins.util.log(
        plugins.util.colors.yellow('Image tasks don\'t run on production.')
      );
      return gulp;
    }

    return gulp.src(paths.svg)
      .pipe(plugins.svg2png())
      .pipe(gulp.dest(paths.out))
  });
};
