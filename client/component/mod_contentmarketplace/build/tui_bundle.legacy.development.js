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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/mod_contentmarketplace/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/mod_contentmarketplace/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!***************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \***************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./layouts/LayoutBannerSidepanelTwoColumn\": \"./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue\",\n\t\"./layouts/LayoutBannerSidepanelTwoColumn.vue\": \"./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue\",\n\t\"./outer_layouts/OuterLayoutBannerSidepanel\": \"./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue\",\n\t\"./outer_layouts/OuterLayoutBannerSidepanel.vue\": \"./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/mod_contentmarketplace/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/mod_contentmarketplace/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue":
/*!***********************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue ***!
  \***********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _LayoutBannerSidepanelTwoColumn_vue_vue_type_template_id_11069852___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852& */ \"./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852&\");\n/* harmony import */ var _LayoutBannerSidepanelTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js& */ \"./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _LayoutBannerSidepanelTwoColumn_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LayoutBannerSidepanelTwoColumn.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _LayoutBannerSidepanelTwoColumn_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_LayoutBannerSidepanelTwoColumn_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _LayoutBannerSidepanelTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _LayoutBannerSidepanelTwoColumn_vue_vue_type_template_id_11069852___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _LayoutBannerSidepanelTwoColumn_vue_vue_type_template_id_11069852___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_LayoutBannerSidepanelTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_LayoutBannerSidepanelTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=style&index=0&lang=scss&":
/*!*********************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=style&index=0&lang=scss& ***!
  \*********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852&":
/*!******************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852& ***!
  \******************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LayoutBannerSidepanelTwoColumn_vue_vue_type_template_id_11069852___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LayoutBannerSidepanelTwoColumn_vue_vue_type_template_id_11069852___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LayoutBannerSidepanelTwoColumn_vue_vue_type_template_id_11069852___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue":
/*!*************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue ***!
  \*************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _OuterLayoutBannerSidepanel_vue_vue_type_template_id_1da8e768___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768& */ \"./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768&\");\n/* harmony import */ var _OuterLayoutBannerSidepanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js& */ \"./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _OuterLayoutBannerSidepanel_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OuterLayoutBannerSidepanel.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _OuterLayoutBannerSidepanel_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_OuterLayoutBannerSidepanel_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _OuterLayoutBannerSidepanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _OuterLayoutBannerSidepanel_vue_vue_type_template_id_1da8e768___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _OuterLayoutBannerSidepanel_vue_vue_type_template_id_1da8e768___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_OuterLayoutBannerSidepanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_OuterLayoutBannerSidepanel_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=style&index=0&lang=scss&":
/*!***********************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=style&index=0&lang=scss& ***!
  \***********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768&":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768& ***!
  \********************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OuterLayoutBannerSidepanel_vue_vue_type_template_id_1da8e768___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OuterLayoutBannerSidepanel_vue_vue_type_template_id_1da8e768___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OuterLayoutBannerSidepanel_vue_vue_type_template_id_1da8e768___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!**********************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/pages sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \**********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./ContentMarketplaceModules\": \"./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue\",\n\t\"./ContentMarketplaceModules.vue\": \"./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/mod_contentmarketplace/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/mod_contentmarketplace/src/pages_sync_^(?:(?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue":
/*!*****************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ContentMarketplaceModules_vue_vue_type_template_id_ff816792___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ContentMarketplaceModules.vue?vue&type=template&id=ff816792& */ \"./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=template&id=ff816792&\");\n/* harmony import */ var _ContentMarketplaceModules_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ContentMarketplaceModules.vue?vue&type=script&lang=js& */ \"./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ContentMarketplaceModules_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _ContentMarketplaceModules_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ContentMarketplaceModules_vue_vue_type_template_id_ff816792___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ContentMarketplaceModules_vue_vue_type_template_id_ff816792___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ContentMarketplaceModules_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_ContentMarketplaceModules_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!****************************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \****************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentMarketplaceModules_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentMarketplaceModules_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ContentMarketplaceModules_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ContentMarketplaceModules.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ContentMarketplaceModules_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=template&id=ff816792&":
/*!************************************************************************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=template&id=ff816792& ***!
  \************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentMarketplaceModules_vue_vue_type_template_id_ff816792___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ContentMarketplaceModules.vue?vue&type=template&id=ff816792& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=template&id=ff816792&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentMarketplaceModules_vue_vue_type_template_id_ff816792___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentMarketplaceModules_vue_vue_type_template_id_ff816792___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?");

/***/ }),

/***/ "./client/component/mod_contentmarketplace/src/tui.json":
/*!**************************************************************!*\
  !*** ./client/component/mod_contentmarketplace/src/tui.json ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"mod_contentmarketplace\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"mod_contentmarketplace\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"mod_contentmarketplace\")\ntui._bundle.addModulesFromContext(\"mod_contentmarketplace/components\", __webpack_require__(\"./client/component/mod_contentmarketplace/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\ntui._bundle.addModulesFromContext(\"mod_contentmarketplace/pages\", __webpack_require__(\"./client/component/mod_contentmarketplace/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"core\": [\n    \"name\"\n  ],\n  \"mod_contentmarketplace\": [\n    \"marketplace_component\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var mod_contentmarketplace_components_outer_layouts_OuterLayoutBannerSidepanel__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! mod_contentmarketplace/components/outer_layouts/OuterLayoutBannerSidepanel */ \"mod_contentmarketplace/components/outer_layouts/OuterLayoutBannerSidepanel\");\n/* harmony import */ var mod_contentmarketplace_components_outer_layouts_OuterLayoutBannerSidepanel__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(mod_contentmarketplace_components_outer_layouts_OuterLayoutBannerSidepanel__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/grid/Grid */ \"tui/components/grid/Grid\");\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/grid/GridItem */ \"tui/components/grid/GridItem\");\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/loading/Loader */ \"tui/components/loading/Loader\");\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/layouts/PageHeading */ \"tui/components/layouts/PageHeading\");\n/* harmony import */ var tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! tui/components/responsive/Responsive */ \"tui/components/responsive/Responsive\");\n/* harmony import */ var tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_5__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    BannerLayout: mod_contentmarketplace_components_outer_layouts_OuterLayoutBannerSidepanel__WEBPACK_IMPORTED_MODULE_0___default.a,\n    Grid: tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1___default.a,\n    GridItem: tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2___default.a,\n    Loader: tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_3___default.a,\n    PageHeading: tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_4___default.a,\n    Responsive: tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_5___default.a\n  },\n  props: {\n    // Image url for banner\n    bannerImageUrl: [Boolean, String],\n    // Display loader over all content\n    loadingFullPage: Boolean,\n    // Display loader over right column content\n    loadingMainContent: Boolean,\n    // Page title\n    title: {\n      required: true,\n      type: String\n    }\n  },\n  data: function data() {\n    return {\n      boundaryDefaults: {\n        small: {\n          gridDirection: 'vertical',\n          gridUnitsLeft: 24,\n          gridUnitsRight: 24\n        },\n        medium: {\n          gridDirection: 'horizontal',\n          gridUnitsLeft: 16,\n          gridUnitsRight: 8\n        },\n        large: {\n          gridDirection: 'horizontal',\n          gridUnitsLeft: 17,\n          gridUnitsRight: 7\n        },\n        xLarge: {\n          gridDirection: 'horizontal',\n          gridUnitsLeft: 18,\n          gridUnitsRight: 6\n        }\n      },\n      currentBoundary: 'xLarge'\n    };\n  },\n  computed: {\n    /**\n     * Return the grid direction\n     *\n     * @return {Number}\n     */\n    gridDirection: function gridDirection() {\n      if (!this.currentBoundary) {\n        return;\n      }\n\n      return this.boundaryDefaults[this.currentBoundary].gridDirection;\n    },\n\n    /**\n     * Return the number of grid units for side panel\n     *\n     * @return {Number}\n     */\n    gridUnitsLeft: function gridUnitsLeft() {\n      if (!this.currentBoundary) {\n        return;\n      }\n\n      return this.boundaryDefaults[this.currentBoundary].gridUnitsLeft;\n    },\n\n    /**\n     * Return the number of grid units for main content\n     *\n     * @return {Number}\n     */\n    gridUnitsRight: function gridUnitsRight() {\n      if (!this.currentBoundary) {\n        return;\n      }\n\n      return this.boundaryDefaults[this.currentBoundary].gridUnitsRight;\n    },\n\n    /**\n     * Return if the grid is stacked\n     *\n     * @return {Bool}\n     */\n    stacked: function stacked() {\n      return this.gridDirection === 'vertical';\n    }\n  },\n  methods: {\n    /**\n     * Handles responsive resizing which wraps the grid layout for this page\n     *\n     * @param {String} boundaryName\n     */\n    resize: function resize(boundaryName) {\n      this.currentBoundary = boundaryName;\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/grid/Grid */ \"tui/components/grid/Grid\");\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/grid/GridItem */ \"tui/components/grid/GridItem\");\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/loading/Loader */ \"tui/components/loading/Loader\");\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/responsive/Responsive */ \"tui/components/responsive/Responsive\");\n/* harmony import */ var tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_3__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Grid: tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0___default.a,\n    GridItem: tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1___default.a,\n    Loader: tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2___default.a,\n    Responsive: tui_components_responsive_Responsive__WEBPACK_IMPORTED_MODULE_3___default.a\n  },\n  props: {\n    // Image url for banner\n    bannerImageUrl: [Boolean, String],\n    // Display loader over all content\n    loading: Boolean\n  },\n  data: function data() {\n    return {\n      boundaryDefaults: {\n        small: {\n          gridDirection: 'vertical',\n          gridUnitsLeft: 12,\n          gridUnitsRight: 12\n        },\n        medium: {\n          gridDirection: 'horizontal',\n          gridUnitsLeft: 0,\n          gridUnitsRight: 24\n        },\n        large: {\n          gridDirection: 'horizontal',\n          gridUnitsLeft: 0,\n          gridUnitsRight: 24\n        }\n      },\n      // Breakpoints for layout, changing these will impact child layouts\n      breakpoints: [{\n        name: 'small',\n        boundaries: [0, 1167]\n      }, {\n        name: 'medium',\n        boundaries: [1165, 1422]\n      }, {\n        name: 'large',\n        boundaries: [1420, 1681]\n      }],\n      currentBoundary: 'large'\n    };\n  },\n  computed: {\n    /**\n     * Construct a background image URL\n     *\n     * @return {String}\n     */\n    bannerImage: function bannerImage() {\n      var url = this.bannerImageUrl;\n      return !url || url === null ? '' : 'url(\"' + encodeURI(url) + '\")';\n    },\n\n    /**\n     * Return the grid direction\n     *\n     * @return {String}\n     */\n    gridDirection: function gridDirection() {\n      if (!this.currentBoundary) {\n        return;\n      }\n\n      return this.boundaryDefaults[this.currentBoundary].gridDirection;\n    },\n\n    /**\n     * Return the number of grid units for side panel\n     *\n     * @return {Number}\n     */\n    gridUnitsLeft: function gridUnitsLeft() {\n      if (!this.currentBoundary) {\n        return;\n      }\n\n      return this.boundaryDefaults[this.currentBoundary].gridUnitsLeft;\n    },\n\n    /**\n     * Return the number of grid units for main content\n     *\n     * @return {Number}\n     */\n    gridUnitsRight: function gridUnitsRight() {\n      if (!this.currentBoundary) {\n        return;\n      }\n\n      return this.boundaryDefaults[this.currentBoundary].gridUnitsRight;\n    },\n\n    /**\n     * Check if the grid is stacked\n     *\n     * @return {Bool}\n     */\n    stacked: function stacked() {\n      return this.gridDirection === 'vertical';\n    }\n  },\n  methods: {\n    /**\n     * Handles responsive resizing which wraps the grid layout for this page\n     *\n     * @param {String} boundaryName\n     */\n    resize: function resize(boundaryName) {\n      this.currentBoundary = boundaryName;\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/layouts/LayoutOneColumn */ \"tui/components/layouts/LayoutOneColumn\");\n/* harmony import */ var tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_datatable_Table__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/datatable/Table */ \"tui/components/datatable/Table\");\n/* harmony import */ var tui_components_datatable_Table__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_Table__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/datatable/HeaderCell */ \"tui/components/datatable/HeaderCell\");\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/datatable/Cell */ \"tui/components/datatable/Cell\");\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_3__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    LayoutOneColumn: tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_0___default.a,\n    Table: tui_components_datatable_Table__WEBPACK_IMPORTED_MODULE_1___default.a,\n    HeaderCell: tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_2___default.a,\n    Cell: tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_3___default.a\n  },\n  props: {\n    marketplaceRecords: {\n      type: Array,\n      required: true,\n      validator: function validator(records) {\n        return records.every(function (record) {\n          return 'name' in record && 'component_name' in record && 'cm_id' in record;\n        });\n      }\n    },\n    heading: {\n      type: String,\n      required: true\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?vue&type=template&id=11069852& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('BannerLayout',{staticClass:\"tui-marketplaceLayoutBannerSidepanelTwoColumn\",attrs:{\"banner-image-url\":_vm.bannerImageUrl,\"loading\":_vm.loadingFullPage},scopedSlots:_vm._u([{key:\"modals\",fn:function(){return [_vm._t(\"modals\")]},proxy:true},(_vm.$scopedSlots['banner-content'])?{key:\"banner-content\",fn:function(ref){\nvar outerStacked = ref.outerStacked;\nreturn [_vm._t(\"banner-content\",null,{\"stacked\":outerStacked})]}}:null,{key:\"main-content\",fn:function(){return [_c('Loader',{staticClass:\"tui-marketplaceLayoutBannerSidepanelTwoColumn__inner\",attrs:{\"loading\":_vm.loadingMainContent}},[_c('Responsive',{attrs:{\"breakpoints\":[\n          { name: 'small', boundaries: [0, 852] },\n          { name: 'medium', boundaries: [850, 972] },\n          { name: 'large', boundaries: [970, 1122] },\n          { name: 'xLarge', boundaries: [1120, 1681] } ]},on:{\"responsive-resize\":_vm.resize}},[_c('Grid',{staticClass:\"tui-marketplaceLayoutBannerSidepanelTwoColumn__grid\",class:{\n            'tui-marketplaceLayoutBannerSidepanelTwoColumn__grid--stacked': _vm.stacked,\n          },attrs:{\"direction\":_vm.gridDirection,\"max-units\":24}},[_c('GridItem',{staticClass:\"tui-marketplaceLayoutBannerSidepanelTwoColumn__main\",attrs:{\"units\":_vm.gridUnitsLeft}},[_vm._t(\"feedback-banner\"),_vm._v(\" \"),_vm._t(\"user-overview\"),_vm._v(\" \"),_c('div',{staticClass:\"tui-marketplaceLayoutBannerSidepanelTwoColumn__heading\"},[_vm._t(\"content-nav\"),_vm._v(\" \"),_c('PageHeading',{attrs:{\"title\":_vm.title},scopedSlots:_vm._u([{key:\"buttons\",fn:function(){return [_vm._t(\"header-buttons\")]},proxy:true}],null,true)})],2),_vm._v(\" \"),_vm._t(\"main-content\")],2),_vm._v(\" \"),_c('GridItem',{staticClass:\"tui-marketplaceLayoutBannerSidepanelTwoColumn__side\",attrs:{\"units\":_vm.gridUnitsRight}},[_vm._t(\"side-content\")],2)],1)],1)],1)]},proxy:true}],null,true)})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/layouts/LayoutBannerSidepanelTwoColumn.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?vue&type=template&id=1da8e768& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel\"},[_c('Loader',{attrs:{\"loading\":_vm.loading}},[_c('Responsive',{attrs:{\"breakpoints\":_vm.breakpoints},on:{\"responsive-resize\":_vm.resize}},[_c('Grid',{attrs:{\"direction\":_vm.gridDirection,\"max-units\":24,\"use-vertical-gap\":false}},[_c('GridItem',{attrs:{\"units\":_vm.gridUnitsLeft}},[_vm._t(\"side-panel\",null,{\"outerStacked\":_vm.stacked})],2),_vm._v(\" \"),_c('GridItem',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel__right\",attrs:{\"units\":_vm.gridUnitsRight}},[_c('div',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel__banner\"},[_c('div',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel__banner-image\",style:({ 'background-image': _vm.bannerImage })}),_vm._v(\" \"),(_vm.$scopedSlots['banner-content'])?_c('div',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel__banner-content\"},[_c('div',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel__banner-contentArea\"},[_vm._t(\"banner-content\",null,{\"outerStacked\":_vm.stacked})],2)]):_vm._e()]),_vm._v(\" \"),_c('div',{staticClass:\"tui-marketplaceOuterLayoutBannerSidepanel__body\"},[_vm._t(\"main-content\",null,{\"outerStacked\":_vm.stacked})],2)])],1)],1)],1),_vm._v(\" \"),_vm._t(\"modals\")],2)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/components/outer_layouts/OuterLayoutBannerSidepanel.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=template&id=ff816792&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?vue&type=template&id=ff816792& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('LayoutOneColumn',{attrs:{\"title\":_vm.heading},scopedSlots:_vm._u([{key:\"content\",fn:function(){return [_c('Table',{attrs:{\"data\":_vm.marketplaceRecords},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',[_vm._v(\"\\n          \"+_vm._s(_vm.$str('name', 'core'))+\"\\n        \")]),_vm._v(\" \"),_c('HeaderCell',[_vm._v(\"\\n          \"+_vm._s(_vm.$str('marketplace_component', 'mod_contentmarketplace'))+\"\\n        \")])]},proxy:true},{key:\"row\",fn:function(ref){\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"column-header\":_vm.$str('name', 'core')}},[_c('a',{attrs:{\"href\":_vm.$url('/mod/contentmarketplace/view.php', { id: row.cm_id })}},[_vm._v(\"\\n            \"+_vm._s(row.name)+\"\\n          \")])]),_vm._v(\" \"),_c('Cell',{attrs:{\"column-header\":_vm.$str('marketplace_component', 'mod_contentmarketplace')}},[_vm._v(\"\\n          \"+_vm._s(row.component_name)+\"\\n        \")])]}}])})]},proxy:true}])})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/mod_contentmarketplace/src/pages/ContentMarketplaceModules.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

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

/***/ "mod_contentmarketplace/components/outer_layouts/OuterLayoutBannerSidepanel":
/*!**************************************************************************************************************!*\
  !*** external "tui.require(\"mod_contentmarketplace/components/outer_layouts/OuterLayoutBannerSidepanel\")" ***!
  \**************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_contentmarketplace/components/outer_layouts/OuterLayoutBannerSidepanel\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_contentmarketplace/components/outer_layouts/OuterLayoutBannerSidepanel\\%22)%22?");

/***/ }),

/***/ "tui/components/datatable/Cell":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/Cell\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/Cell\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/Cell\\%22)%22?");

/***/ }),

/***/ "tui/components/datatable/HeaderCell":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/HeaderCell\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/HeaderCell\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/HeaderCell\\%22)%22?");

/***/ }),

/***/ "tui/components/datatable/Table":
/*!******************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/Table\")" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/Table\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/Table\\%22)%22?");

/***/ }),

/***/ "tui/components/grid/Grid":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/grid/Grid\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/grid/Grid\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/grid/Grid\\%22)%22?");

/***/ }),

/***/ "tui/components/grid/GridItem":
/*!****************************************************************!*\
  !*** external "tui.require(\"tui/components/grid/GridItem\")" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/grid/GridItem\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/grid/GridItem\\%22)%22?");

/***/ }),

/***/ "tui/components/layouts/LayoutOneColumn":
/*!**************************************************************************!*\
  !*** external "tui.require(\"tui/components/layouts/LayoutOneColumn\")" ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/layouts/LayoutOneColumn\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/layouts/LayoutOneColumn\\%22)%22?");

/***/ }),

/***/ "tui/components/layouts/PageHeading":
/*!**********************************************************************!*\
  !*** external "tui.require(\"tui/components/layouts/PageHeading\")" ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/layouts/PageHeading\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/layouts/PageHeading\\%22)%22?");

/***/ }),

/***/ "tui/components/loading/Loader":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/loading/Loader\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/loading/Loader\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/loading/Loader\\%22)%22?");

/***/ }),

/***/ "tui/components/responsive/Responsive":
/*!************************************************************************!*\
  !*** external "tui.require(\"tui/components/responsive/Responsive\")" ***!
  \************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/responsive/Responsive\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/responsive/Responsive\\%22)%22?");

/***/ })

/******/ });