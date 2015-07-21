module.exports = function (gulp, plugins, config) {
  gulp.task('serve', ['watch'], function () {

    // Set isServing so `watch` can detect if it needs it's own LiveReload.
    config.isServing = true;

    gulp.src(config.dist)
      .pipe(plugins.webserver({
        livereload: true,
        port: 8080,
        open: plugins.util.env.open || false
      }));
  });
};
