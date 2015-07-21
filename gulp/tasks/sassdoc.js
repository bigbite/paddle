module.exports = function (gulp, plugins, config) {
  var paths = config.paths.docs;

  gulp.task('sassdoc', function () {
    return gulp.src(paths.sassdoc)
      .pipe(plugins.sassdoc({
        'dest': paths.src + '/sassdoc',
        'theme': 'neat'
      }));
  });
};
