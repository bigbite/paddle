var path = require('path');
var webpack = require('webpack');
var config = require('./gulp/config');

module.exports = {
  cache: true,
  entry: {
    app: path.join(__dirname, 'src/scripts/app/app.js'),
    vendor: ['jquery', 'postal'],
  },
  output: {
    library: 'app',
    libraryTarget: 'window',
    path: path.join(__dirname, 'public/assets/scripts'),
    publicPath: '/assets/',
    filename: 'bundle.js'
  },
  noParse: [
    path.join(__dirname, 'bower_components/jQuery'),
    path.join(__dirname, 'bower_components/materialize')
  ],
  module: {
    preLoaders: [
      { test: /(\.js)|(\.jsx)$/, include: /(src\/scripts)/, loader: 'jsxhint-loader' }
    ],
    loaders: [
      { test: /(\.js)|(\.jsx)$/, include: /(src\/scripts)/, loader: 'babel-loader?optional=runtime' }
    ]
  },
  externals: {
    // 'jquery-global': 'jQuery',
  },
  plugins: [
    new webpack.ResolverPlugin(
      new webpack.ResolverPlugin.DirectoryDescriptionFilePlugin('bower.json', ['main'])
    ),
    new webpack.ProvidePlugin({
      '$': 'jquery',
      'postal': 'postal'
    }),
    new webpack.optimize.CommonsChunkPlugin(/* chunkName= */'vendor', /* filename= */'vendor.js'),
    new webpack.BannerPlugin('Copyright (c) ' + new Date().getFullYear() + ' Big Bite Creative | bigbitecreative.com | @bigbitecreative'),
    // new webpack.optimize.UglifyJsPlugin()
  ],
  jshint: {
    emitErrors: false,
    failOnHint: false,
  },
  resolve: {
    root: [ path.join(__dirname, 'bower_components') ]
  }
};
