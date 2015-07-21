/**
 * Main dirs.
 */
var srcDir  = 'src';
var distDir = 'public';
var docsDir = 'docs';
var testDir = 'tests';

/**
 * Globally accessible options.
 */
module.exports = {

  ENV: process.env.NODE_ENV = process.env.NODE_ENV || 'dev',

  // Tasks to use. Named as filenames in `gulp-tasks` folder.
  tasks: [
    'bower',
    'browser-sync',
    'clean',
    'docs',
    'favicons',
    'images',
    // 'js-unit',
    'sassdoc',
    'serve',
    'static',
    'styles',
    'svg2png',
    'watch',
    'webpack',
  ],

  // Base paths.
  src:  srcDir,
  dist: distDir,
  test: testDir,

  // Task paths.
  paths: {
    styles: {
      src: srcDir + '/styles/app.scss',
      out: distDir + '/assets/styles'
    },

    scripts: {
      entry: srcDir + '/scripts/app/app.js',
      src: [
        srcDir + '/scripts/app/app.js',
        srcDir + '/scripts/app/modules/**/*.js'
      ],
      out: distDir + '/assets/scripts'
    },

    bower: {
      out: distDir + '/assets/scripts/'
    },

    images: {
      src: srcDir + '/images/**/*.{jpg,jpeg,png,gif,svg}',
      svg: srcDir + '/images/**/*.svg',
      out: distDir + '/assets/images'
    },

    static: {
      src: srcDir + '/static/**',
      out: distDir + '/assets/'
    },

    livereload: {
      src: distDir + '/**/*.{html,twig,php,css,js,map,png,jpg,jpeg,gif,svg}'
    },

    watch: [
      { src: srcDir + '/styles/**', task: ['styles'] },
      { src: srcDir + '/scripts/**', task: ['webpack:build-dev'] },
      { src: srcDir + '/images/**/*.{png,jpg,jpeg,gif,svg}', task: ['images'] },
      { src: srcDir + '/static/**', task: ['static'] }
    ],

    docs: {
      src: docsDir,
      sassdoc: srcDir + '/styles/**/*.scss'
    },

    tests: {
      js: {
        src: [
          srcDir + '/scripts/app/app.js',
          srcDir + '/scripts/app/**/module.js',
          srcDir + '/scripts/app/**/config.js',
          srcDir + '/scripts/app/**/*.js'
        ],
        specs: [
          testDir + '/scripts/lib/jquery.js',
          testDir + '/scripts/unit/**/*.spec.js'
        ]
      }
    },

    clean: {
      dev: distDir + '/assets',
      production: [
        distDir + '/assets/scripts',
        distDir + '/assets/styles'
      ]
    }
  },

  // Used for LiveReload
  isServing: false,
  isBrowserSync: false,

  // Copyright banner
  banner: '/*! Copyright (c) ' + new Date().getFullYear() + ' Big Bite Creative | bigbitecreative.com | @bigbitecreative */\n'
}
