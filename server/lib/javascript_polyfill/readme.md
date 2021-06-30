Description of import of polyfill libraries
===========================================

Totara LXP uses the core-js to polyfill ES6 features, and MDN to polyfill DOM features for IE11.
Set.from is in draft stage 1 https://tc39.es/proposal-setmap-offrom/#sec-set.from.
To account for this, the core-js Set polyfill is bundled and included as the `esnext_features` polyfill in all browsers

Constructing the `/server/lib/javascript_polyfill/dom/dom_features_ie11.js` file contents is a manual process to ensure we're only
adding polyfills that we need.

Building the ES polyfill bundles:

1. Change directory to `/server/lib/javascript_polyfill/`
2. Run `npm install`
3. Run `npm run main`
4. Change directory to `/server/`
5. Run `./node_modules/.bin/grunt uglify:independent` task

The commands will transpile and bundle ES5 versions of the es6/ files and place them in src/.
The grunt `uglify:independent` task will copy and minify these files and place them in build/.
See server/grunt.txt server/Gruntfile.js.

The DOM polyfills for IE11 are in src/dom_features_ie11.js.
These do not need to be transpiled but are concatenated to the ./src/es6_dom_features.bundle.js file.

ES6 Promise
------------

Promise polyfill is required in IE11 when not using jQuery.

1. Go to https://github.com/stefanpenner/es6-promise
2. Override existing file with downloaded es6-promise.auto.js
3. Do not change any whitespace or formatting
4. Update version in /lib/thirdpartylibs.xml
5. Use totara/core/dev/fix_file_permissions.php to fix file permissions

At the time of this commit, at least core-js 3.10.1 - 3.15.1 introduce a bug in the Promise polyfill
that might be a race condition and hard to detect. For the time being, we will retain the earlier
polyfill for Promises.

window.fetch
------------

Requires promise polyfill in IE11.

1. Download release from https://github.com/github/fetch
2. Override current file with fetch.js
3. Do not change any whitespace or formatting
4. Update version in /lib/thirdpartylibs.xml
5. Copy LICENSE file if updated
6. Use totara/core/dev/fix_file_permissions.php to fix file permissions
