module.exports = function (gulp, plugins, config) {
  var paths = config.paths;

  gulp.task('watch', function () {

    // If using BrowserSync, use it's own reload system, else use livereload.
    var reload = config.isBrowserSync ? plugins.browserSync.reload : plugins.livereload.changed;

    // LiveReload / BrowserSync reload. Don't load if using `serve` as that has it's own LiveReload injection.
    if (!config.isServing) {
      plugins.livereload.listen();
      gulp.watch(paths.livereload.src).on('change', reload);
    }

    // Loop config watch paths and run watch.
    paths.watch.forEach(function (watch) {
      gulp.watch(watch.src, watch.task)
    });
  });

};
