/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/contentmarketplaceactivity_linkedin/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./side-panel/LinkedInActivityContentsTree\": \"./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue\",\n\t\"./side-panel/LinkedInActivityContentsTree.vue\": \"./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/contentmarketplaceactivity_linkedin/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/contentmarketplaceactivity_linkedin/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue":
/*!*************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue ***!
  \*************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _LinkedInActivityContentsTree_vue_vue_type_template_id_41b563fa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa& */ \"./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa&\");\n/* harmony import */ var _LinkedInActivityContentsTree_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LinkedInActivityContentsTree.vue?vue&type=script&lang=js& */ \"./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _LinkedInActivityContentsTree_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _LinkedInActivityContentsTree_vue_vue_type_template_id_41b563fa___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _LinkedInActivityContentsTree_vue_vue_type_template_id_41b563fa___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_LinkedInActivityContentsTree_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./LinkedInActivityContentsTree.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_LinkedInActivityContentsTree_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!../../../../../tooling/webpack/css_raw_loader.js??ref--4-1!../../../../../../node_modules/postcss-loader/src??ref--4-2!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss& */ \"./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);\n/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if([\"default\"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));\n /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); \n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa&":
/*!********************************************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa& ***!
  \********************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_template_id_41b563fa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_template_id_41b563fa___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LinkedInActivityContentsTree_vue_vue_type_template_id_41b563fa___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!***********************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/pages sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \***********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./ActivityView\": \"./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue\",\n\t\"./ActivityView.vue\": \"./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/contentmarketplaceactivity_linkedin/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/contentmarketplaceactivity_linkedin/src/pages_sync_^(?:(?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue":
/*!*****************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ActivityView_vue_vue_type_template_id_1c8c7495___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ActivityView.vue?vue&type=template&id=1c8c7495& */ \"./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=template&id=1c8c7495&\");\n/* harmony import */ var _ActivityView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ActivityView.vue?vue&type=script&lang=js& */ \"./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ActivityView.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ActivityView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ActivityView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ActivityView_vue_vue_type_template_id_1c8c7495___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ActivityView_vue_vue_type_template_id_1c8c7495___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ActivityView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_ActivityView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!****************************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \****************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ActivityView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ActivityView.vue?vue&type=script&lang=js& */ \"./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ActivityView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!../../../../tooling/webpack/css_raw_loader.js??ref--4-1!../../../../../node_modules/postcss-loader/src??ref--4-2!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActivityView.vue?vue&type=style&index=0&lang=scss& */ \"./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);\n/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if([\"default\"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));\n /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_4_0_tooling_webpack_css_raw_loader_js_ref_4_1_node_modules_postcss_loader_src_index_js_ref_4_2_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); \n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=template&id=1c8c7495&":
/*!************************************************************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=template&id=1c8c7495& ***!
  \************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_template_id_1c8c7495___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ActivityView.vue?vue&type=template&id=1c8c7495& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=template&id=1c8c7495&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_template_id_1c8c7495___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ActivityView_vue_vue_type_template_id_1c8c7495___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?");

/***/ }),

/***/ "./client/component/contentmarketplaceactivity_linkedin/src/tui.json":
/*!***************************************************************************!*\
  !*** ./client/component/contentmarketplaceactivity_linkedin/src/tui.json ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"contentmarketplaceactivity_linkedin\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"contentmarketplaceactivity_linkedin\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"contentmarketplaceactivity_linkedin\")\ntui._bundle.addModulesFromContext(\"contentmarketplaceactivity_linkedin/components\", __webpack_require__(\"./client/component/contentmarketplaceactivity_linkedin/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\ntui._bundle.addModulesFromContext(\"contentmarketplaceactivity_linkedin/pages\", __webpack_require__(\"./client/component/contentmarketplaceactivity_linkedin/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"mod_contentmarketplace\": [\n    \"a11y_activity_difficulty\",\n    \"a11y_activity_time_to_complete\",\n    \"activity_contents\",\n    \"activity_set_self_completion\",\n    \"activity_status_completed\",\n    \"activity_status_in_progress\",\n    \"activity_status_not_started\",\n    \"course_details\",\n    \"enrol_to_course\",\n    \"enrol_success_message\",\n    \"internal_error\",\n    \"launch_in_new_window\",\n    \"toggle_off_error\",\n    \"toggle_on_error\",\n    \"updated_at\",\n    \"viewing_as_enrollable_admin\",\n    \"viewing_as_enrollable_admin_self_enrol_disabled\",\n    \"viewing_as_enrollable_guest\",\n    \"viewing_as_guest\"\n  ],\n  \"core_enrol\": [\n    \"enrol\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_tree_Tree__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/tree/Tree */ \"tui/components/tree/Tree\");\n/* harmony import */ var tui_components_tree_Tree__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_tree_Tree__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Tree: (tui_components_tree_Tree__WEBPACK_IMPORTED_MODULE_0___default()),\n  },\n\n  props: {\n    /**\n     * Tree data for contents\n     */\n    treeData: {\n      type: Array,\n      required: true,\n    },\n    /**\n     * List of open branches\n     */\n    value: {\n      type: Array,\n      required: true,\n    },\n  },\n\n  data() {\n    return {\n      open: this.value,\n    };\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_card_ActionCard__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/card/ActionCard */ \"tui/components/card/ActionCard\");\n/* harmony import */ var tui_components_card_ActionCard__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_card_ActionCard__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_settings_navigation_SettingsNavigation__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/settings_navigation/SettingsNavigation */ \"tui/components/settings_navigation/SettingsNavigation\");\n/* harmony import */ var tui_components_settings_navigation_SettingsNavigation__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_settings_navigation_SettingsNavigation__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/buttons/Button */ \"tui/components/buttons/Button\");\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var mod_contentmarketplace_components_layouts_LayoutBannerTwoColumn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! mod_contentmarketplace/components/layouts/LayoutBannerTwoColumn */ \"mod_contentmarketplace/components/layouts/LayoutBannerTwoColumn\");\n/* harmony import */ var mod_contentmarketplace_components_layouts_LayoutBannerTwoColumn__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(mod_contentmarketplace_components_layouts_LayoutBannerTwoColumn__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_lozenge_Lozenge__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/lozenge/Lozenge */ \"tui/components/lozenge/Lozenge\");\n/* harmony import */ var tui_components_lozenge_Lozenge__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_lozenge_Lozenge__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var tui_components_notifications_NotificationBanner__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! tui/components/notifications/NotificationBanner */ \"tui/components/notifications/NotificationBanner\");\n/* harmony import */ var tui_components_notifications_NotificationBanner__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(tui_components_notifications_NotificationBanner__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var tui_components_layouts_PageBackLink__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! tui/components/layouts/PageBackLink */ \"tui/components/layouts/PageBackLink\");\n/* harmony import */ var tui_components_layouts_PageBackLink__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(tui_components_layouts_PageBackLink__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var tui_components_toggle_ToggleSwitch__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! tui/components/toggle/ToggleSwitch */ \"tui/components/toggle/ToggleSwitch\");\n/* harmony import */ var tui_components_toggle_ToggleSwitch__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(tui_components_toggle_ToggleSwitch__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var tui_components_tree_util__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! tui/components/tree/util */ \"tui/components/tree/util\");\n/* harmony import */ var tui_components_tree_util__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(tui_components_tree_util__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var tui_notifications__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! tui/notifications */ \"tui/notifications\");\n/* harmony import */ var tui_notifications__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(tui_notifications__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var contentmarketplaceactivity_linkedin_graphql_linkedin_activity__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! contentmarketplaceactivity_linkedin/graphql/linkedin_activity */ \"./server/mod/contentmarketplace/contentmarketplaces/linkedin/webapi/ajax/linkedin_activity.graphql\");\n/* harmony import */ var mod_contentmarketplace_graphql_set_self_completion__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! mod_contentmarketplace/graphql/set_self_completion */ \"./server/mod/contentmarketplace/webapi/ajax/set_self_completion.graphql\");\n/* harmony import */ var mod_contentmarketplace_graphql_request_non_interactive_enrol__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! mod_contentmarketplace/graphql/request_non_interactive_enrol */ \"./server/mod/contentmarketplace/webapi/ajax/request_non_interactive_enrol.graphql\");\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n\n\n\n\n// Utils\n\n\n\n// GraphQL\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ActionCard: (tui_components_card_ActionCard__WEBPACK_IMPORTED_MODULE_0___default()),\n    AdminMenu: (tui_components_settings_navigation_SettingsNavigation__WEBPACK_IMPORTED_MODULE_1___default()),\n    Button: (tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_2___default()),\n    Layout: (mod_contentmarketplace_components_layouts_LayoutBannerTwoColumn__WEBPACK_IMPORTED_MODULE_3___default()),\n    Lozenge: (tui_components_lozenge_Lozenge__WEBPACK_IMPORTED_MODULE_4___default()),\n    NotificationBanner: (tui_components_notifications_NotificationBanner__WEBPACK_IMPORTED_MODULE_5___default()),\n    PageBackLink: (tui_components_layouts_PageBackLink__WEBPACK_IMPORTED_MODULE_6___default()),\n    ToggleSwitch: (tui_components_toggle_ToggleSwitch__WEBPACK_IMPORTED_MODULE_7___default()),\n  },\n\n  props: {\n    /**\n     * The course's module id, not the content marketplace id.\n     */\n    cmId: {\n      type: Number,\n      required: true,\n    },\n\n    /**\n     * Check it has notification or not.\n     */\n    hasNotification: {\n      type: Boolean,\n      required: true,\n    },\n  },\n\n  data() {\n    return {\n      interactor: {\n        // Can self enrol in activity\n        canEnrol: false,\n        // Viewing activity as guest (not enrolled in activity)\n        canLaunch: false,\n        hasViewCapability: false,\n        isEnrolled: false,\n        isSiteGuest: false,\n        nonEnrolInstanceEnabled: false,\n        supportsNonInteractiveEnrol: false,\n      },\n      setCompletion: false,\n      // Open nodes of contents tree\n      openContents: [],\n      webLaunchUrl: null,\n      ssoLaunchUrl: null,\n      redirctEnrolUrl: null,\n    };\n  },\n\n  computed: {\n    canEnrolActivity() {\n      const { canEnrol, isSiteGuest } = this.interactor;\n      return canEnrol && !isSiteGuest;\n    },\n\n    launchInNewWindowDisabled() {\n      const { canEnrol, isSiteGuest, canLaunch } = this.interactor;\n      if (!canLaunch) {\n        return true;\n      }\n\n      if (isSiteGuest) {\n        return true;\n      }\n\n      return canEnrol;\n    },\n\n    displayBannerInfo() {\n      const {\n        hasViewCapability,\n        isSiteGuest,\n        nonEnrolInstanceEnabled,\n      } = this.interactor;\n\n      if (hasViewCapability) {\n        return nonEnrolInstanceEnabled\n          ? this.$str('viewing_as_enrollable_admin', 'mod_contentmarketplace')\n          : this.$str(\n              'viewing_as_enrollable_admin_self_enrol_disabled',\n              'mod_contentmarketplace'\n            );\n      }\n\n      return nonEnrolInstanceEnabled && !isSiteGuest\n        ? this.$str('viewing_as_enrollable_guest', 'mod_contentmarketplace')\n        : this.$str('viewing_as_guest', 'mod_contentmarketplace');\n    },\n\n    displayEnrolButton() {\n      const { nonEnrolInstanceEnabled, isSiteGuest } = this.interactor;\n      return nonEnrolInstanceEnabled && !isSiteGuest;\n    },\n  },\n\n  mounted() {\n    if (this.hasNotification) {\n      Object(tui_notifications__WEBPACK_IMPORTED_MODULE_9__[\"notify\"])({\n        message: this.$str('enrol_success_message', 'mod_contentmarketplace'),\n        type: 'success',\n      });\n    }\n  },\n\n  apollo: {\n    activity: {\n      query: contentmarketplaceactivity_linkedin_graphql_linkedin_activity__WEBPACK_IMPORTED_MODULE_10__[\"default\"],\n      variables() {\n        return {\n          cm_id: this.cmId,\n        };\n      },\n      update({ instance: data }) {\n        const contentsTree = [];\n\n        const { learning_object, module } = data;\n        const has_course_view_page =\n          module.course.course_format.has_course_view_page || false;\n        const learningObject = {\n          contentsTree: contentsTree,\n          course: {\n            name: module.course.fullname,\n            url: has_course_view_page ? module.course.url : null,\n          },\n          description: module.intro,\n          image: module.course.image,\n          levelString: learning_object.level,\n          name: module.name,\n          // Default to completion not started\n          status: this.$str(\n            'activity_status_not_started',\n            'mod_contentmarketplace'\n          ),\n          completionEnabled: module.completion_enabled,\n          timeToComplete: learning_object.time_to_complete,\n          updated: learning_object.last_updated_at,\n          selfCompletion: module.self_completion,\n        };\n\n        this.webLaunchUrl = learning_object.web_launch_url;\n        this.ssoLaunchUrl = learning_object.sso_launch_url;\n        this.redirctEnrolUrl = module.redirect_enrol_url;\n\n        // When the field completion_status is null, meaning that user is not yet started,\n        // hence setCompletion should be False.\n        this.setCompletion = module.completion_status || false;\n        this.openContents = Object(tui_components_tree_util__WEBPACK_IMPORTED_MODULE_8__[\"getAllNodeKeys\"])(contentsTree);\n\n        const { interactor } = module;\n        this.interactor.canEnrol = interactor.can_enrol;\n        this.interactor.hasViewCapability = interactor.has_view_capability;\n        this.interactor.isSiteGuest = interactor.is_site_guest;\n        this.interactor.canLaunch = interactor.can_launch;\n        this.interactor.isEnrolled = interactor.is_enrolled;\n        this.interactor.nonEnrolInstanceEnabled =\n          interactor.non_interactive_enrol_instance_enabled;\n        this.interactor.supportsNonInteractiveEnrol =\n          interactor.supports_non_interactive_enrol;\n\n        if (module.completion_status !== null) {\n          // The completion of this activity had been started and it is either completed or in progress\n          // base upon the value of this field \"completion_status\".\n          if (module.completion_status) {\n            learningObject.status = this.$str(\n              'activity_status_completed',\n              'mod_contentmarketplace'\n            );\n          } else {\n            learningObject.status = this.$str(\n              'activity_status_in_progress',\n              'mod_contentmarketplace'\n            );\n          }\n        }\n\n        return learningObject;\n      },\n    },\n  },\n\n  methods: {\n    launchNewWindow() {\n      // This window name is used for behat.\n      const windowName = 'linkedIn_course_window';\n      if (this.ssoLaunchUrl) {\n        window.open(this.ssoLaunchUrl, windowName);\n        return;\n      }\n\n      // If ssoLaunchUrl is null, it will fallback to webLaunchUrl.\n      window.open(this.webLaunchUrl, windowName);\n    },\n\n    async setCompletionHandler() {\n      try {\n        let {\n          data: { result },\n        } = await this.$apollo.mutate({\n          mutation: mod_contentmarketplace_graphql_set_self_completion__WEBPACK_IMPORTED_MODULE_11__[\"default\"],\n          refetchAll: false,\n          variables: {\n            cm_id: this.cmId,\n            status: this.setCompletion,\n          },\n          update: (store, { data: { result: completionResult } }) => {\n            // Update the completion result to apollo cache.\n            let { instance } = store.readQuery({\n              query: contentmarketplaceactivity_linkedin_graphql_linkedin_activity__WEBPACK_IMPORTED_MODULE_10__[\"default\"],\n              variables: {\n                cm_id: this.cmId,\n              },\n            });\n\n            instance = Object.assign({}, instance);\n            instance.module = Object.assign({}, instance.module, {\n              completion_status: completionResult,\n            });\n\n            store.writeQuery({\n              query: contentmarketplaceactivity_linkedin_graphql_linkedin_activity__WEBPACK_IMPORTED_MODULE_10__[\"default\"],\n              variables: {\n                cm_id: this.cmId,\n              },\n              data: { instance },\n            });\n          },\n        });\n\n        this.setCompletion = result;\n      } catch (e) {\n        await Object(tui_notifications__WEBPACK_IMPORTED_MODULE_9__[\"notify\"])({\n          message: this.$str(\n            this.setCompletion ? 'toggle_on_error' : 'toggle_off_error',\n            'mod_contentmarketplace'\n          ),\n          type: 'error',\n        });\n      }\n    },\n\n    async requestNonInteractiveEnrol() {\n      const { supportsNonInteractiveEnrol } = this.interactor;\n      if (!supportsNonInteractiveEnrol) {\n        window.location.href = this.redirctEnrolUrl;\n        return;\n      }\n\n      try {\n        const variables = { cm_id: this.cmId };\n\n        let {\n          data: { result },\n        } = await this.$apollo.mutate({\n          mutation: mod_contentmarketplace_graphql_request_non_interactive_enrol__WEBPACK_IMPORTED_MODULE_12__[\"default\"],\n          variables,\n          refetchAll: true,\n        });\n\n        if (result) {\n          await Object(tui_notifications__WEBPACK_IMPORTED_MODULE_9__[\"notify\"])({\n            message: this.$str(\n              'enrol_success_message',\n              'mod_contentmarketplace'\n            ),\n            type: 'success',\n          });\n        }\n      } catch (e) {\n        await Object(tui_notifications__WEBPACK_IMPORTED_MODULE_9__[\"notify\"])({\n          message: this.$str('internal_error', 'mod_contentmarketplace'),\n          type: 'error',\n        });\n      }\n    },\n  },\n});\n\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./client/tooling/webpack/css_raw_loader.js?!./node_modules/postcss-loader/src/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?./node_modules/mini-css-extract-plugin/dist/loader.js??ref--4-0!./client/tooling/webpack/css_raw_loader.js??ref--4-1!./node_modules/postcss-loader/src??ref--4-2!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?vue&type=template&id=41b563fa& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('Tree',{staticClass:\"tui-linkedinActivityContentTree\",attrs:{\"tree-data\":_vm.treeData},on:{\"input\":function($event){return _vm.$emit('input', $event)}},scopedSlots:_vm._u([{key:\"custom-label\",fn:function(ref){\nvar label = ref.label;\nreturn [_vm._v(\"\\n    \"+_vm._s(label)+\"\\n  \")]}},{key:\"content\",fn:function(ref){\nvar content = ref.content;\nreturn [_c('div',{staticClass:\"tui-linkedinActivityContentTree__contents\"},[_vm._l((content.items),function(item,i){return [_c('div',{key:i,staticClass:\"tui-linkedinActivityContentTree__contents-item\"},[_vm._v(\"\\n          \"+_vm._s(item)+\"\\n        \")])]})],2)]}}]),model:{value:(_vm.open),callback:function ($$v) {_vm.open=$$v},expression:\"open\"}})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/components/side-panel/LinkedInActivityContentsTree.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=template&id=1c8c7495&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?vue&type=template&id=1c8c7495& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.activity)?_c('Layout',{staticClass:\"tui-linkedinActivity\",attrs:{\"banner-image-url\":_vm.activity.image,\"loading-full-page\":_vm.$apollo.loading,\"title\":_vm.activity.name},scopedSlots:_vm._u([(_vm.activity.course.url)?{key:\"content-nav\",fn:function(){return [_c('PageBackLink',{attrs:{\"link\":_vm.activity.course.url,\"text\":_vm.activity.course.name}})]},proxy:true}:null,{key:\"banner-content\",fn:function(ref){\nvar stacked = ref.stacked;\nreturn [_c('div',{staticClass:\"tui-linkedinActivity__admin\"},[_c('AdminMenu',{attrs:{\"stacked-layout\":stacked}})],1)]}},{key:\"feedback-banner\",fn:function(){return [(_vm.canEnrolActivity)?_c('NotificationBanner',{attrs:{\"type\":\"info\"},scopedSlots:_vm._u([{key:\"body\",fn:function(){return [_c('ActionCard',{attrs:{\"no-border\":true},scopedSlots:_vm._u([{key:\"card-body\",fn:function(){return [_vm._v(\"\\n            \"+_vm._s(_vm.displayBannerInfo)+\"\\n          \")]},proxy:true},{key:\"card-action\",fn:function(){return [(_vm.displayEnrolButton)?_c('Button',{attrs:{\"styleclass\":{ primary: 'true' },\"title\":_vm.$str(\n                  'enrol_to_course',\n                  'mod_contentmarketplace',\n                  _vm.activity.course.name\n                ),\"text\":_vm.$str('enrol', 'core_enrol')},on:{\"click\":_vm.requestNonInteractiveEnrol}}):_vm._e()]},proxy:true}],null,false,241737355)})]},proxy:true}],null,false,3708923513)}):(_vm.interactor.isSiteGuest)?_c('NotificationBanner',{attrs:{\"message\":_vm.$str('viewing_as_guest', 'mod_contentmarketplace'),\"type\":\"info\"}}):_vm._e()]},proxy:true},{key:\"main-content\",fn:function(){return [_c('div',{staticClass:\"tui-linkedinActivity__body\"},[_c('Button',{attrs:{\"disabled\":_vm.launchInNewWindowDisabled,\"styleclass\":{ primary: 'true' },\"text\":_vm.$str('launch_in_new_window', 'mod_contentmarketplace')},on:{\"click\":_vm.launchNewWindow}}),_vm._v(\" \"),(_vm.activity.completionEnabled && _vm.interactor.isEnrolled)?_c('div',{staticClass:\"tui-linkedinActivity__status\"},[_c('div',{staticClass:\"tui-linkedinActivity__status-completion\"},[_c('Lozenge',{attrs:{\"text\":_vm.activity.status}})],1),_vm._v(\" \"),(_vm.activity.selfCompletion)?_c('ToggleSwitch',{staticClass:\"tui-linkedinActivity__status-toggle\",attrs:{\"text\":_vm.$str('activity_set_self_completion', 'mod_contentmarketplace'),\"toggle-first\":true},on:{\"input\":_vm.setCompletionHandler},model:{value:(_vm.setCompletion),callback:function ($$v) {_vm.setCompletion=$$v},expression:\"setCompletion\"}}):_vm._e()],1):_vm._e(),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedinActivity__details\"},[_c('h3',{staticClass:\"tui-linkedinActivity__details-header\"},[_vm._v(\"\\n          \"+_vm._s(_vm.$str('course_details', 'mod_contentmarketplace'))+\"\\n        \")]),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedinActivity__details-content\"},[_c('div',{staticClass:\"tui-linkedinActivity__details-bar\"},[_c('div',[_c('span',{staticClass:\"sr-only\"},[_vm._v(\"\\n                \"+_vm._s(_vm.$str(\n                    'a11y_activity_time_to_complete',\n                    'mod_contentmarketplace'\n                  ))+\"\\n              \")]),_vm._v(\"\\n              \"+_vm._s(_vm.activity.timeToComplete)+\"\\n            \")]),_vm._v(\" \"),_c('div',[_c('span',{staticClass:\"sr-only\"},[_vm._v(\"\\n                \"+_vm._s(_vm.$str('a11y_activity_difficulty', 'mod_contentmarketplace'))+\"\\n              \")]),_vm._v(\"\\n              \"+_vm._s(_vm.activity.levelString)+\"\\n            \")]),_vm._v(\" \"),_c('div',[_vm._v(\"\\n              \"+_vm._s(_vm.$str('updated_at', 'mod_contentmarketplace', _vm.activity.updated))+\"\\n            \")])]),_vm._v(\" \"),_c('div',{staticClass:\"tui-linkedinActivity__details-desc\",domProps:{\"innerHTML\":_vm._s(_vm.activity.description)}})])])],1)]},proxy:true}],null,true)}):_vm._e()}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/contentmarketplaceactivity_linkedin/src/pages/ActivityView.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return normalizeComponent; });\n/* globals __VUE_SSR_CONTEXT__ */\n\n// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).\n// This module is a runtime utility for cleaner component module output and will\n// be included in the final webpack user bundle.\n\nfunction normalizeComponent (\n  scriptExports,\n  render,\n  staticRenderFns,\n  functionalTemplate,\n  injectStyles,\n  scopeId,\n  moduleIdentifier, /* server only */\n  shadowMode /* vue-cli only */\n) {\n  // Vue.extend constructor export interop\n  var options = typeof scriptExports === 'function'\n    ? scriptExports.options\n    : scriptExports\n\n  // render functions\n  if (render) {\n    options.render = render\n    options.staticRenderFns = staticRenderFns\n    options._compiled = true\n  }\n\n  // functional template\n  if (functionalTemplate) {\n    options.functional = true\n  }\n\n  // scopedId\n  if (scopeId) {\n    options._scopeId = 'data-v-' + scopeId\n  }\n\n  var hook\n  if (moduleIdentifier) { // server build\n    hook = function (context) {\n      // 2.3 injection\n      context =\n        context || // cached call\n        (this.$vnode && this.$vnode.ssrContext) || // stateful\n        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional\n      // 2.2 with runInNewContext: true\n      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {\n        context = __VUE_SSR_CONTEXT__\n      }\n      // inject component styles\n      if (injectStyles) {\n        injectStyles.call(this, context)\n      }\n      // register component module identifier for async chunk inferrence\n      if (context && context._registeredComponents) {\n        context._registeredComponents.add(moduleIdentifier)\n      }\n    }\n    // used by ssr in case component is cached and beforeCreate\n    // never gets called\n    options._ssrRegister = hook\n  } else if (injectStyles) {\n    hook = shadowMode\n      ? function () {\n        injectStyles.call(\n          this,\n          (options.functional ? this.parent : this).$root.$options.shadowRoot\n        )\n      }\n      : injectStyles\n  }\n\n  if (hook) {\n    if (options.functional) {\n      // for template-only hot-reload because in that case the render fn doesn't\n      // go through the normalizer\n      options._injectStyles = hook\n      // register for functional component in vue file\n      var originalRender = options.render\n      options.render = function renderWithStyleInjection (h, context) {\n        hook.call(context)\n        return originalRender(h, context)\n      }\n    } else {\n      // inject component registration as beforeCreate hook\n      var existing = options.beforeCreate\n      options.beforeCreate = existing\n        ? [].concat(existing, hook)\n        : [hook]\n    }\n  }\n\n  return {\n    exports: scriptExports,\n    options: options\n  }\n}\n\n\n//# sourceURL=webpack:///./node_modules/vue-loader/lib/runtime/componentNormalizer.js?");

/***/ }),

/***/ "./server/mod/contentmarketplace/contentmarketplaces/linkedin/webapi/ajax/linkedin_activity.graphql":
/*!**********************************************************************************************************!*\
  !*** ./server/mod/contentmarketplace/contentmarketplaces/linkedin/webapi/ajax/linkedin_activity.graphql ***!
  \**********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"contentmarketplaceactivity_linkedin_linkedin_activity\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"core_id\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"instance\"},\"name\":{\"kind\":\"Name\",\"value\":\"contentmarketplaceactivity_linkedin_linkedin_activity\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"module\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"course\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"fullname\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"image\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"url\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"course_format\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"has_course_view_page\"},\"arguments\":[],\"directives\":[]}]}}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"intro\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"format\"},\"value\":{\"kind\":\"EnumValue\",\"value\":\"HTML\"}}],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"completion_condition\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"completion_status\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"completion_enabled\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"self_completion\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"redirect_enrol_url\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"interactor\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"has_view_capability\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"can_enrol\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"can_launch\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"is_site_guest\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"is_enrolled\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"non_interactive_enrol_instance_enabled\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"supports_non_interactive_enrol\"},\"arguments\":[],\"directives\":[]}]}}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"learning_object\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"asset_type\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"level\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"time_to_complete\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"last_updated_at\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"web_launch_url\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"sso_launch_url\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/contentmarketplace/contentmarketplaces/linkedin/webapi/ajax/linkedin_activity.graphql?");

/***/ }),

/***/ "./server/mod/contentmarketplace/webapi/ajax/request_non_interactive_enrol.graphql":
/*!*****************************************************************************************!*\
  !*** ./server/mod/contentmarketplace/webapi/ajax/request_non_interactive_enrol.graphql ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"mutation\",\"name\":{\"kind\":\"Name\",\"value\":\"mod_contentmarketplace_request_non_interactive_enrol\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"core_id\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"result\"},\"name\":{\"kind\":\"Name\",\"value\":\"mod_contentmarketplace_request_non_interactive_enrol\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"}}}],\"directives\":[]}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/contentmarketplace/webapi/ajax/request_non_interactive_enrol.graphql?");

/***/ }),

/***/ "./server/mod/contentmarketplace/webapi/ajax/set_self_completion.graphql":
/*!*******************************************************************************!*\
  !*** ./server/mod/contentmarketplace/webapi/ajax/set_self_completion.graphql ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"mutation\",\"name\":{\"kind\":\"Name\",\"value\":\"mod_contentmarketplace_set_self_completion\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"core_id\"}}},\"directives\":[]},{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"status\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"param_boolean\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"result\"},\"name\":{\"kind\":\"Name\",\"value\":\"mod_contentmarketplace_set_self_completion\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"cm_id\"}}},{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"status\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"status\"}}}],\"directives\":[]}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/contentmarketplace/webapi/ajax/set_self_completion.graphql?");

/***/ }),

/***/ "mod_contentmarketplace/components/layouts/LayoutBannerTwoColumn":
/*!***************************************************************************************************!*\
  !*** external "tui.require(\"mod_contentmarketplace/components/layouts/LayoutBannerTwoColumn\")" ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_contentmarketplace/components/layouts/LayoutBannerTwoColumn\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_contentmarketplace/components/layouts/LayoutBannerTwoColumn\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/Button":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/Button\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/Button\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/Button\\%22)%22?");

/***/ }),

/***/ "tui/components/card/ActionCard":
/*!******************************************************************!*\
  !*** external "tui.require(\"tui/components/card/ActionCard\")" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/card/ActionCard\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/card/ActionCard\\%22)%22?");

/***/ }),

/***/ "tui/components/layouts/PageBackLink":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/layouts/PageBackLink\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/layouts/PageBackLink\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/layouts/PageBackLink\\%22)%22?");

/***/ }),

/***/ "tui/components/lozenge/Lozenge":
/*!******************************************************************!*\
  !*** external "tui.require(\"tui/components/lozenge/Lozenge\")" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/lozenge/Lozenge\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/lozenge/Lozenge\\%22)%22?");

/***/ }),

/***/ "tui/components/notifications/NotificationBanner":
/*!***********************************************************************************!*\
  !*** external "tui.require(\"tui/components/notifications/NotificationBanner\")" ***!
  \***********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/notifications/NotificationBanner\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/notifications/NotificationBanner\\%22)%22?");

/***/ }),

/***/ "tui/components/settings_navigation/SettingsNavigation":
/*!*****************************************************************************************!*\
  !*** external "tui.require(\"tui/components/settings_navigation/SettingsNavigation\")" ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/settings_navigation/SettingsNavigation\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/settings_navigation/SettingsNavigation\\%22)%22?");

/***/ }),

/***/ "tui/components/toggle/ToggleSwitch":
/*!**********************************************************************!*\
  !*** external "tui.require(\"tui/components/toggle/ToggleSwitch\")" ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/toggle/ToggleSwitch\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/toggle/ToggleSwitch\\%22)%22?");

/***/ }),

/***/ "tui/components/tree/Tree":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/tree/Tree\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/tree/Tree\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/tree/Tree\\%22)%22?");

/***/ }),

/***/ "tui/components/tree/util":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/tree/util\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/tree/util\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/tree/util\\%22)%22?");

/***/ }),

/***/ "tui/notifications":
/*!*****************************************************!*\
  !*** external "tui.require(\"tui/notifications\")" ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/notifications\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/notifications\\%22)%22?");

/***/ })

/******/ });