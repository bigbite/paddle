module.exports = function (gulp, plugins, config) {
  var paths = config.paths.styles;

  gulp.task('styles', function () {
    return gulp.src(paths.src)
      .pipe(plugins.plumber())
      .pipe(config.ENV === 'dev' ? plugins.sourcemaps.init() : plugins.util.noop())
        .pipe(plugins.sass({
          outputStyle: config.ENV === 'dev' ? 'nested' : 'compressed',

          // Pretty errors.
          onError: function (err) {
            plugins.util.log(
              [
                '',
                plugins.util.colors.red.bold.underline('Sass compile error:'),
                plugins.util.colors.gray('> ') + err.message,
                plugins.util.colors.gray('> ') + 'Line ' + err.line,
                plugins.util.colors.gray('> ') + err.file,
                ''
              ].join("\n")
            )
          }
        }))
        .pipe(plugins.autoprefixer('last 2 version', '> 1%'))
        .pipe(plugins.wrapper({ header: config.banner }))
      .pipe(config.ENV === 'dev' ? plugins.sourcemaps.write('.') : plugins.util.noop())
      .pipe(gulp.dest(paths.out));
  });

};
