Description of import of polyfill libraries
===========================================

Totara LXP uses the core-js to polyfill ES6 features, and MDN to polyfill DOM features for IE11.
Set.from is in draft stage 1 https://tc39.es/proposal-setmap-offrom/#sec-set.from.
To account for this, the core-js Set polyfill is bundled and included as the `esnext_features` polyfill in all browsers 

Building the ES polyfill bundles:

1. Install webpack https://webpack.js.org/guides/getting-started/.
2. Change directory to server/lib/javascript_polyfill.
3. Run `webpack --config webpack.es6_dom_features.js`.
4. Run `webpack --config webpack.esnext_features.js`.
5. Run the grunt `uglify:independent` task.

The commands will transpile and bundle ES5 versions of the es6/ files and place them in src/. 
The grunt `uglify:independent` task will copy and minify these files and place them in build/.
See server/grunt.txt server/Gruntfile.js.

The DOM polyfills for IE11 are in src/dom_features_ie11.js. 
These do not need to be transpiled but are concatenated to the ./src/es6_dom_features.bundle.js file.

window.fetch
------------

Requires promise polyfil in IE11.

1. Download release from https://github.com/github/fetch
2. Override current file with fetch.js
3. Do not change any whitespace or formatting
4. Update version in /lib/thirdpartylibs.xml
5. Copy LICENSE file if updated
6. Use totara/core/dev/fix_file_permissions.php to fix file permissions


