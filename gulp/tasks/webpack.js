// var webpack = require('gulp-webpack');
// var WebpackDevServer = require('webpack-dev-server');

var gutil = require('gulp-util');
var webpack = require('webpack');
var webpackConfig = require('../../webpack.config.js');

module.exports = function (gulp, plugins, config) {

  /**
   * Production builds.
   */
  gulp.task('webpack:build', function (callback) {

    // Modify some webpack config options.
    var myConfig = Object.create(webpackConfig);

    myConfig.plugins = myConfig.plugins.concat(
      new webpack.DefinePlugin({
        'process.env': {

          // This has effect on the react lib size.
          'NODE_ENV': JSON.stringify('production')
        }
      }),
      new webpack.optimize.DedupePlugin(),
      new webpack.optimize.UglifyJsPlugin()
    );

    // Run webpack.
    webpack(myConfig, function (err, stats) {
      if (err) {
        throw new gutil.PluginError('webpack:build', err);
      }

      gutil.log('[webpack:build]', stats.toString({
        colors: true
      }));

      callback();
    });
  });


  /**
   * Development builds.
   */

  // Modify some webpack config options.
  var myDevConfig = Object.create(webpackConfig);

  myDevConfig.devtool = 'sourcemap';
  myDevConfig.debug = true;

  // Create a single instance of the compiler to allow caching.
  var devCompiler = webpack(myDevConfig);

  gulp.task('webpack:build-dev', function(callback) {

    // Run webpack.
    devCompiler.run(function (err, stats) {
      if (err) {
        throw new gutil.PluginError('webpack:build-dev', err);
      }

      gutil.log('[webpack:build-dev]', stats.toString({
        colors: true
      }));

      callback();
    });
  });

  /**
   * Alternate method using `gulp-webpack`. Sets webpack to watch.
   */
  // var paths = config.paths.scripts;
  //
  // gulp.task('webpack', function (callback) {
  //   webpackConfig.watch = true;
  //
  //   return gulp.src(paths.entry)
  //     .pipe(webpack(webpackConfig))
  //     .pipe(gulp.dest(paths.out))
  // });
}
