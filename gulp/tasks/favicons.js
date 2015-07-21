module.exports = function (gulp, plugins, config) {
  gulp.task('favicons:clean', function (cb) {
    plugins.del('public/assets/favicons/', cb);
  });

  gulp.task('favicons', ['favicons:clean'], function () {
    return gulp.src('src/favicons/index.html')
      .pipe(plugins.favicons({
        files: {
          src: 'src/favicons/favicon.png',
          dest: '../../public/assets/favicons/'
        },
        icons: {
          appleStartup: false,
        }
      }))
      .pipe(gulp.dest('src/favicons'))
  });
};
