module.exports = function (gulp, plugins, config) {
  var paths = config.paths.tests.js;

  gulp.task('test:js-unit', function () {
    return gulp.src(paths.src.concat(paths.specs))
    .pipe(karma({
      configFile: config.test + '/scripts/karma.conf.js'
    }))
    .on('error', function (err) {
      throw err;
    });
  });
}
