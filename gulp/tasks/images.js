module.exports = function (gulp, plugins, config) {
  var paths = config.paths.images;

  gulp.task('images', function () {
    if (config.ENV === 'production') {
      plugins.util.log(
        plugins.util.colors.yellow('Image tasks don\'t run on production.')
      );
      return gulp;
    }

    return gulp.src(paths.src)
      .pipe(plugins.changed(paths.src))
      .pipe(plugins.imagemin({

        // jpg
        progressive: true,

        // gif
        interlaced: true,

        // svg
        svgoPlugins: [
          {removeViewBox: false},            // Keep viewBox attr
          {cleanupIDs: false},               // Keep ID's
          {removeHiddenElems: false},        // Keep opacity="0" elems
          {_collections: false},             // Keep preserveAspectRatio
          {removeUnknownsAndDefaults: false} // Keep ID on <svg> tag
        ],

        // plugins
        use: [

          /**
           * pngquat, better compression than the default optipng
           * http://pointlessramblings.com/posts/pngquant_vs_pngcrush_vs_optipng_vs_pngnq
           */
          plugins.pngquant({
            quality: '75-80',
            speed: 4
          })
        ]
      }))
      .pipe(gulp.dest(paths.out))
  });
}
