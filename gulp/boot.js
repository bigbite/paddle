var gulp = require('gulp');
var gutil = require('gulp-util');
var config = require('./config');

// Override NODE_ENV when using `gulp <task> --production`.
config.ENV = gutil.env.production ? 'production' : 'dev';

// Load in plugins based on env.
var plugins = require('gulp-load-plugins')({
  scope: config.ENV === 'production' ? ['dependencies'] : ['dependencies', 'devDependencies']
});

// Non-gulp plugins required on all environments.
plugins.del = require('del');
plugins.bower = require('main-bower-files');

// Non-gulp plugins only required on dev environment.
if (config.ENV === 'dev') {
  plugins.browserSync = require('browser-sync').create();
  plugins.pngquant = require('imagemin-pngquant');
  plugins.sassdoc = require('sassdoc');
  plugins.stylish = require('jshint-stylish');
}

// Require each task, pass in gulp, plugins and config.
config.tasks.forEach(function (task) {
  require('./tasks/' + task)(gulp, plugins, config);
});
