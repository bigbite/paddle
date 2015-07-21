module.exports = function (gulp, plugins, config) {
  var paths = config.paths.livereload;

  gulp.task('browser-sync', ['watch'], function () {

    // Let watch know we're using BrowserSync
    config.isBrowserSync = true;

    plugins.browserSync.init({
      open: plugins.util.env.open || false,
      notify: plugins.util.env.notify || false,

      // Create server
      server: {
        baseDir: config.dist
      },

      // Or use existing vhost
      // proxy: 'local.dev'
    });
  });
};
