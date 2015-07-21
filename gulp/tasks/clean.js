module.exports = function (gulp, plugins, config) {
  var paths = config.paths.clean;

  gulp.task('clean', function (cb) {
    plugins.del(config.ENV === 'dev' ? paths.dev : paths.production, cb);
  });
};
