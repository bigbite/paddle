# Base
> A base for kickstarting projects.

## The New Hotness (JS)
We’re now using Webpack to build our JS, for several reasons. Firstly, it allows us to use ES6 modules in all their glory; secondly, it manages our dependencies/third-party libraries without us having to dump the source files into a static folder, or run separate Bower tasks to bundle them up.

You’ll notice that there is a new `webpack.config.js` file in the root; there’s a lot of content in there, but there are only a couple of things you need worry about. We pre-build with jQuery, lodash, and Postal, so you’ll only touch the config if you require additional libraries (such as socket.io, React, etc).

To require an additional third-party library, ‘install’ it via npm, if available, or bower. Once that’s done, you’ll need to add it to the vendor array of the module.exports object [here](http://jon.moe/1cVUx/Qph9BEvi). To make the library available to your modules, import it into any modules that require it, or if it will be used a lot, you can automatically provide it to all modules by adding it to Webpacks’s ProvidePlugin object [here](http://jon.moe/1krpX/TD1TZeXN).

If you require the library to be available to the DOM, you will need to export it from the main `app.js` file, as we have with Postal, [here](http://jon.moe/13QaZ/1P7l4hPX). That’s it!

### Building webpack bundles.
Run `gulp webpack:build`, and your library will now be available for use. `gulp watch` will run `webpack:build-dev`, which will not Uglify code, so make sure to run `gulp webpack:build` before committing.

## Documentation
### Sass
See Sass documentation by running `sassdoc:serve`.

## Gulp tasks
### Core
- `default` - Runs styles, bower, scripts, static, images.
- `build` - Cleans, then runs default task.

### Individual
- `bower` — Compiles Bower components.
- `browser-sync` — Launches BrowserSync server, watches files for change. Use `--open` flag to open page in browser. Use `--noftify` flag to enable in-browser BowserSync notifications.
- `clean` - Cleans out the build files.
- `docs` - Spin up a local server to view docs.
- `images` - Compresses `.{jpg,jpeg,png,gif,svg}` in `src/images/`
- `sassdoc` - Generate Sass documentation in `docs/sassdoc/`.
- `serve` - Spin up a HTTP server with LiveReload. Use `--open` flag to open page in browser.
- `static` - Compile assets in `src/static` individually. Useful for fonts and single scripts etc.
- `styles` - Compile Sass from `src/styles/`.
- `svg2png` - Convert .svg files in `src/images/` to `.png`.
- `watch` - Watch files for change, with LiveReload.
- `webpack:build-dev` - Webpack development build. Includes sourcemaps and no uglify. Used by `watch`.
- `webpack:build` - Webpack production build. Uglifies.

### Environment flags
Use the `--production` flag to run in production mode. This minifies Sass and uglifies JS. When using the `--production` flag, image tasks will be ignored.

### Server build
Run `npm run build` on the server to run server specific tasks in production mode. An alias for `gulp server-build --production`.

### Tests
- `test:js-unit` - Run JavaScript unit tests.
