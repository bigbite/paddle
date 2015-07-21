module.exports = function (gulp, plugins, config) {
  var paths = config.paths.static;

  gulp.task('static', function () {

    // Filters
    var cssFilter = plugins.filter('**/*.css');

    return gulp.src(paths.src)
      .pipe(plugins.plumber())
      .pipe(plugins.changed(paths.out))

      // .css filter
      .pipe(cssFilter)
        .pipe(plugins.autoprefixer('last 2 version', '> 1%'))
        .pipe(config.ENV === 'dev' ? plugins.util.noop() : plugins.minifyCss())
        .pipe(plugins.wrapper({ header: config.banner }))
      .pipe(cssFilter.restore())

      .pipe(gulp.dest(paths.out));
  });
};
