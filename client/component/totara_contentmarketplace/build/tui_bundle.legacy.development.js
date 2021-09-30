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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/totara_contentmarketplace/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/totara_contentmarketplace/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!******************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./basket/ImportBasket\": \"./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue\",\n\t\"./basket/ImportBasket.vue\": \"./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue\",\n\t\"./count/ImportCountAndFilters\": \"./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue\",\n\t\"./count/ImportCountAndFilters.vue\": \"./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue\",\n\t\"./filters/ImportSortFilter\": \"./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue\",\n\t\"./filters/ImportSortFilter.vue\": \"./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue\",\n\t\"./layouts/PageLayoutTwoColumn\": \"./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue\",\n\t\"./layouts/PageLayoutTwoColumn.vue\": \"./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue\",\n\t\"./paging/ImportReviewLoadMore\": \"./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue\",\n\t\"./paging/ImportReviewLoadMore.vue\": \"./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue\",\n\t\"./paging/ImportSelectionPaging\": \"./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue\",\n\t\"./paging/ImportSelectionPaging.vue\": \"./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue\",\n\t\"./tables/ImportReviewTable\": \"./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue\",\n\t\"./tables/ImportReviewTable.vue\": \"./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue\",\n\t\"./tables/ImportSelectionTable\": \"./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue\",\n\t\"./tables/ImportSelectionTable.vue\": \"./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/totara_contentmarketplace/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/totara_contentmarketplace/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue":
/*!*******************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportBasket_vue_vue_type_template_id_2d1cf99a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportBasket.vue?vue&type=template&id=2d1cf99a& */ \"./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=template&id=2d1cf99a&\");\n/* harmony import */ var _ImportBasket_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportBasket.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ImportBasket_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ImportBasket.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _ImportBasket_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ImportBasket_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ImportBasket_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ImportBasket_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportBasket_vue_vue_type_template_id_2d1cf99a___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportBasket_vue_vue_type_template_id_2d1cf99a___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ImportBasket_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_ImportBasket_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!******************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \******************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportBasket_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportBasket_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportBasket_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportBasket.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportBasket_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=style&index=0&lang=scss&":
/*!*****************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=style&index=0&lang=scss& ***!
  \*****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=template&id=2d1cf99a&":
/*!**************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=template&id=2d1cf99a& ***!
  \**************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportBasket_vue_vue_type_template_id_2d1cf99a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportBasket.vue?vue&type=template&id=2d1cf99a& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=template&id=2d1cf99a&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportBasket_vue_vue_type_template_id_2d1cf99a___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportBasket_vue_vue_type_template_id_2d1cf99a___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue":
/*!***************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportCountAndFilters_vue_vue_type_template_id_a512d6a6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportCountAndFilters.vue?vue&type=template&id=a512d6a6& */ \"./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=template&id=a512d6a6&\");\n/* harmony import */ var _ImportCountAndFilters_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportCountAndFilters.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ImportCountAndFilters_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ImportCountAndFilters.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _ImportCountAndFilters_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ImportCountAndFilters_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ImportCountAndFilters_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ImportCountAndFilters_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportCountAndFilters_vue_vue_type_template_id_a512d6a6___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportCountAndFilters_vue_vue_type_template_id_a512d6a6___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ImportCountAndFilters_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_ImportCountAndFilters_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**************************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportCountAndFilters_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportCountAndFilters_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportCountAndFilters_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportCountAndFilters.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportCountAndFilters_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=template&id=a512d6a6&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=template&id=a512d6a6& ***!
  \**********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportCountAndFilters_vue_vue_type_template_id_a512d6a6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportCountAndFilters.vue?vue&type=template&id=a512d6a6& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=template&id=a512d6a6&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportCountAndFilters_vue_vue_type_template_id_a512d6a6___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportCountAndFilters_vue_vue_type_template_id_a512d6a6___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue":
/*!************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue ***!
  \************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportSortFilter_vue_vue_type_template_id_7bd74f40___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportSortFilter.vue?vue&type=template&id=7bd74f40& */ \"./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=template&id=7bd74f40&\");\n/* harmony import */ var _ImportSortFilter_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportSortFilter.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ImportSortFilter_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ImportSortFilter.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _ImportSortFilter_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ImportSortFilter_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ImportSortFilter_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ImportSortFilter_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportSortFilter_vue_vue_type_template_id_7bd74f40___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportSortFilter_vue_vue_type_template_id_7bd74f40___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ImportSortFilter_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_ImportSortFilter_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSortFilter_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSortFilter_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportSortFilter_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportSortFilter.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportSortFilter_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=style&index=0&lang=scss&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=style&index=0&lang=scss& ***!
  \**********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=template&id=7bd74f40&":
/*!*******************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=template&id=7bd74f40& ***!
  \*******************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSortFilter_vue_vue_type_template_id_7bd74f40___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportSortFilter.vue?vue&type=template&id=7bd74f40& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=template&id=7bd74f40&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSortFilter_vue_vue_type_template_id_7bd74f40___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSortFilter_vue_vue_type_template_id_7bd74f40___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue":
/*!***************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _PageLayoutTwoColumn_vue_vue_type_template_id_425e36a2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2& */ \"./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2&\");\n/* harmony import */ var _PageLayoutTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PageLayoutTwoColumn.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _PageLayoutTwoColumn_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PageLayoutTwoColumn.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _PageLayoutTwoColumn_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_PageLayoutTwoColumn_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _PageLayoutTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _PageLayoutTwoColumn_vue_vue_type_template_id_425e36a2___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _PageLayoutTwoColumn_vue_vue_type_template_id_425e36a2___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_PageLayoutTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./PageLayoutTwoColumn.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_PageLayoutTwoColumn_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2& ***!
  \**********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PageLayoutTwoColumn_vue_vue_type_template_id_425e36a2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PageLayoutTwoColumn_vue_vue_type_template_id_425e36a2___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PageLayoutTwoColumn_vue_vue_type_template_id_425e36a2___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue":
/*!***************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportReviewLoadMore_vue_vue_type_template_id_1fa477ba___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba& */ \"./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba&\");\n/* harmony import */ var _ImportReviewLoadMore_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportReviewLoadMore.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ImportReviewLoadMore_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ImportReviewLoadMore.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _ImportReviewLoadMore_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ImportReviewLoadMore_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _ImportReviewLoadMore_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ImportReviewLoadMore_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportReviewLoadMore_vue_vue_type_template_id_1fa477ba___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportReviewLoadMore_vue_vue_type_template_id_1fa477ba___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _ImportReviewLoadMore_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_ImportReviewLoadMore_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**************************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewLoadMore_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewLoadMore_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportReviewLoadMore_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportReviewLoadMore.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportReviewLoadMore_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba& ***!
  \**********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewLoadMore_vue_vue_type_template_id_1fa477ba___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewLoadMore_vue_vue_type_template_id_1fa477ba___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewLoadMore_vue_vue_type_template_id_1fa477ba___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue":
/*!****************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportSelectionPaging_vue_vue_type_template_id_b0b04a3e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e& */ \"./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e&\");\n/* harmony import */ var _ImportSelectionPaging_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportSelectionPaging.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _ImportSelectionPaging_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportSelectionPaging_vue_vue_type_template_id_b0b04a3e___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportSelectionPaging_vue_vue_type_template_id_b0b04a3e___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportSelectionPaging_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportSelectionPaging.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportSelectionPaging_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e&":
/*!***********************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e& ***!
  \***********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSelectionPaging_vue_vue_type_template_id_b0b04a3e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSelectionPaging_vue_vue_type_template_id_b0b04a3e___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSelectionPaging_vue_vue_type_template_id_b0b04a3e___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue":
/*!************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue ***!
  \************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportReviewTable_vue_vue_type_template_id_c0992cd0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportReviewTable.vue?vue&type=template&id=c0992cd0& */ \"./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=template&id=c0992cd0&\");\n/* harmony import */ var _ImportReviewTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportReviewTable.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _ImportReviewTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportReviewTable_vue_vue_type_template_id_c0992cd0___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportReviewTable_vue_vue_type_template_id_c0992cd0___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportReviewTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportReviewTable.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportReviewTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=template&id=c0992cd0&":
/*!*******************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=template&id=c0992cd0& ***!
  \*******************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewTable_vue_vue_type_template_id_c0992cd0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportReviewTable.vue?vue&type=template&id=c0992cd0& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=template&id=c0992cd0&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewTable_vue_vue_type_template_id_c0992cd0___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportReviewTable_vue_vue_type_template_id_c0992cd0___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue":
/*!***************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _ImportSelectionTable_vue_vue_type_template_id_24293760___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ImportSelectionTable.vue?vue&type=template&id=24293760& */ \"./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=template&id=24293760&\");\n/* harmony import */ var _ImportSelectionTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ImportSelectionTable.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _ImportSelectionTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ImportSelectionTable.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _ImportSelectionTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ImportSelectionTable_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _ImportSelectionTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _ImportSelectionTable_vue_vue_type_template_id_24293760___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _ImportSelectionTable_vue_vue_type_template_id_24293760___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportSelectionTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./ImportSelectionTable.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_ImportSelectionTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=style&index=0&lang=scss&":
/*!*************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=template&id=24293760&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=template&id=24293760& ***!
  \**********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSelectionTable_vue_vue_type_template_id_24293760___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ImportSelectionTable.vue?vue&type=template&id=24293760& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=template&id=24293760&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSelectionTable_vue_vue_type_template_id_24293760___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ImportSelectionTable_vue_vue_type_template_id_24293760___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!*************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/pages sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \*************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./CatalogImportLayout\": \"./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue\",\n\t\"./CatalogImportLayout.vue\": \"./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/totara_contentmarketplace/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/totara_contentmarketplace/src/pages_sync_^(?:(?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue":
/*!**************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _CatalogImportLayout_vue_vue_type_template_id_6865c015___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CatalogImportLayout.vue?vue&type=template&id=6865c015& */ \"./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=template&id=6865c015&\");\n/* harmony import */ var _CatalogImportLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CatalogImportLayout.vue?vue&type=script&lang=js& */ \"./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _CatalogImportLayout_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CatalogImportLayout.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _CatalogImportLayout_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_CatalogImportLayout_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _CatalogImportLayout_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _CatalogImportLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _CatalogImportLayout_vue_vue_type_template_id_6865c015___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _CatalogImportLayout_vue_vue_type_template_id_6865c015___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _CatalogImportLayout_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_CatalogImportLayout_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*************************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CatalogImportLayout_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CatalogImportLayout_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_CatalogImportLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./CatalogImportLayout.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_CatalogImportLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=style&index=0&lang=scss&":
/*!************************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=style&index=0&lang=scss& ***!
  \************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=template&id=6865c015&":
/*!*********************************************************************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=template&id=6865c015& ***!
  \*********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CatalogImportLayout_vue_vue_type_template_id_6865c015___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CatalogImportLayout.vue?vue&type=template&id=6865c015& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=template&id=6865c015&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CatalogImportLayout_vue_vue_type_template_id_6865c015___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CatalogImportLayout_vue_vue_type_template_id_6865c015___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?");

/***/ }),

/***/ "./client/component/totara_contentmarketplace/src/tui.json":
/*!*****************************************************************!*\
  !*** ./client/component/totara_contentmarketplace/src/tui.json ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"totara_contentmarketplace\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"totara_contentmarketplace\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"totara_contentmarketplace\")\ntui._bundle.addModulesFromContext(\"totara_contentmarketplace/components\", __webpack_require__(\"./client/component/totara_contentmarketplace/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\ntui._bundle.addModulesFromContext(\"totara_contentmarketplace/pages\", __webpack_require__(\"./client/component/totara_contentmarketplace/src/pages sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_contentmarketplace\": [\n    \"assign_category\",\n    \"basket_back_to_catalogue\",\n    \"basket_clear_selection\",\n    \"basket_create_courses\",\n    \"basket_go_to_review\",\n    \"basket_select_category\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_contentmarketplace\": [\n    \"active_filter\",\n    \"active_filter_first\",\n    \"active_filter_last\",\n    \"item_count\",\n    \"item_count_and_filters\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_contentmarketplace\": [\n    \"filter_sort_by\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_contentmarketplace\": [\n    \"load_more\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"totara_contentmarketplace\": [\n    \"a11y_filter_panel\",\n    \"a11y_import_page_reviewing\",\n    \"a11y_import_page_selecting\",\n    \"hide_filters\",\n    \"show_filters\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_basket_Basket__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/basket/Basket */ \"tui/components/basket/Basket\");\n/* harmony import */ var tui_components_basket_Basket__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_basket_Basket__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/buttons/Button */ \"tui/components/buttons/Button\");\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_form_Select__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/form/Select */ \"tui/components/form/Select\");\n/* harmony import */ var tui_components_form_Select__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_form_Select__WEBPACK_IMPORTED_MODULE_2__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Basket: tui_components_basket_Basket__WEBPACK_IMPORTED_MODULE_0___default.a,\n    Button: tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_1___default.a,\n    Select: tui_components_form_Select__WEBPACK_IMPORTED_MODULE_2___default.a\n  },\n  props: {\n    // Available category options\n    categoryOptions: Array,\n    // Category chosen from select list\n    selectedCategory: [String, Number],\n    // List of selected item ID's\n    selectedItems: {\n      type: Array,\n      required: true\n    },\n    // On the viewing selected screen\n    viewingSelected: Boolean,\n    creatingContent: Boolean\n  },\n  computed: {\n    /**\n     * Provide list of available options with a default\n     *\n     * @return {Array}\n     */\n    options: function options() {\n      var optionList = this.categoryOptions.slice(); // Add default string\n\n      optionList.unshift({\n        id: null,\n        label: this.$str('assign_category', 'totara_contentmarketplace')\n      });\n      return optionList;\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  props: {\n    // Count of active filters\n    count: {\n      type: Number,\n      required: true\n    },\n    // Active filters\n    filters: Array\n  },\n  computed: {\n    /**\n     * Return a concatenated string of active filters\n     * e.g. \"Data science\" AND \"Videos\"\n     *\n     * @return {String}\n     */\n    activeFilters: function activeFilters() {\n      var _this = this;\n\n      if (!this.filters.length) {\n        return '';\n      }\n\n      var text = '';\n      this.filters.forEach(function (filter, index) {\n        text += index === 0 ? _this.$str('active_filter_first', 'totara_contentmarketplace', filter) : index === _this.filters.length - 1 ? _this.$str('active_filter_last', 'totara_contentmarketplace', filter) : _this.$str('active_filter', 'totara_contentmarketplace', filter);\n      });\n      return text;\n    },\n\n    /**\n     * Format count number\n     *\n     * @return {String}\n     */\n    formattedCount: function formattedCount() {\n      return this.count.toLocaleString();\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_form_Label__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/form/Label */ \"tui/components/form/Label\");\n/* harmony import */ var tui_components_form_Label__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_form_Label__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_form_Select__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/form/Select */ \"tui/components/form/Select\");\n/* harmony import */ var tui_components_form_Select__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_form_Select__WEBPACK_IMPORTED_MODULE_1__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Label: tui_components_form_Label__WEBPACK_IMPORTED_MODULE_0___default.a,\n    Select: tui_components_form_Select__WEBPACK_IMPORTED_MODULE_1___default.a\n  },\n  props: {\n    options: {\n      type: Array,\n      required: true\n    },\n    sortBy: {\n      type: String,\n      required: true\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/grid/Grid */ \"tui/components/grid/Grid\");\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/grid/GridItem */ \"tui/components/grid/GridItem\");\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/loading/Loader */ \"tui/components/loading/Loader\");\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/layouts/PageHeading */ \"tui/components/layouts/PageHeading\");\n/* harmony import */ var tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_3__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Grid: tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_0___default.a,\n    GridItem: tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_1___default.a,\n    Loader: tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_2___default.a,\n    PageHeading: tui_components_layouts_PageHeading__WEBPACK_IMPORTED_MODULE_3___default.a\n  },\n  props: {\n    // Correctly space if using flush design\n    flush: Boolean,\n    // Display loader over all content\n    loading: Boolean,\n    // Display loader over right column content\n    loadingRight: Boolean,\n    // Custom stack at value\n    stackAt: {\n      type: Number,\n      \"default\": 1000\n    },\n    // Page title\n    title: {\n      required: true,\n      type: String\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/buttons/Button */ \"tui/components/buttons/Button\");\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n// Components\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Button: tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_0___default.a\n  },\n  props: {\n    lastPage: Boolean\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_paging_Paging__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/paging/Paging */ \"tui/components/paging/Paging\");\n/* harmony import */ var tui_components_paging_Paging__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_paging_Paging__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n// Components\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Paging: tui_components_paging_Paging__WEBPACK_IMPORTED_MODULE_0___default.a\n  },\n  props: {\n    currentPage: {\n      type: Number,\n      required: true\n    },\n    itemsPerPage: {\n      type: Number,\n      \"default\": 20\n    },\n    totalItems: {\n      type: Number,\n      required: true\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/datatable/Cell */ \"tui/components/datatable/Cell\");\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/datatable/HeaderCell */ \"tui/components/datatable/HeaderCell\");\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/datatable/SelectTable */ \"tui/components/datatable/SelectTable\");\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n// Components\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Cell: tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0___default.a,\n    HeaderCell: tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1___default.a,\n    SelectTable: tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2___default.a\n  },\n  props: {\n    // Course data\n    items: {\n      type: Array,\n      required: true\n    },\n    // Used for populating accessibility string on rows\n    rowLabelKey: {\n      type: String,\n      required: true\n    },\n    // List of selected item ID's\n    selectedItems: {\n      type: Array,\n      required: true\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/datatable/Cell */ \"tui/components/datatable/Cell\");\n/* harmony import */ var tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/datatable/HeaderCell */ \"tui/components/datatable/HeaderCell\");\n/* harmony import */ var tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/datatable/SelectTable */ \"tui/components/datatable/SelectTable\");\n/* harmony import */ var tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n// Components\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Cell: tui_components_datatable_Cell__WEBPACK_IMPORTED_MODULE_0___default.a,\n    HeaderCell: tui_components_datatable_HeaderCell__WEBPACK_IMPORTED_MODULE_1___default.a,\n    SelectTable: tui_components_datatable_SelectTable__WEBPACK_IMPORTED_MODULE_2___default.a\n  },\n  props: {\n    // Course data\n    items: {\n      type: Array,\n      required: true\n    },\n    // Used for populating accessibility string on rows\n    rowLabelKey: {\n      type: String,\n      required: true\n    },\n    // List of selected item ID's\n    selectedItems: {\n      type: Array,\n      required: true\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/buttons/ButtonIcon */ \"tui/components/buttons/ButtonIcon\");\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/grid/Grid */ \"tui/components/grid/Grid\");\n/* harmony import */ var tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/grid/GridItem */ \"tui/components/grid/GridItem\");\n/* harmony import */ var tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_collapsible_HideShow__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/collapsible/HideShow */ \"tui/components/collapsible/HideShow\");\n/* harmony import */ var tui_components_collapsible_HideShow__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_collapsible_HideShow__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/layouts/LayoutOneColumn */ \"tui/components/layouts/LayoutOneColumn\");\n/* harmony import */ var tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var totara_contentmarketplace_components_layouts_PageLayoutTwoColumn__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! totara_contentmarketplace/components/layouts/PageLayoutTwoColumn */ \"totara_contentmarketplace/components/layouts/PageLayoutTwoColumn\");\n/* harmony import */ var totara_contentmarketplace_components_layouts_PageLayoutTwoColumn__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(totara_contentmarketplace_components_layouts_PageLayoutTwoColumn__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var tui_components_icons_Slider__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! tui/components/icons/Slider */ \"tui/components/icons/Slider\");\n/* harmony import */ var tui_components_icons_Slider__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(tui_components_icons_Slider__WEBPACK_IMPORTED_MODULE_6__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ButtonIcon: tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_0___default.a,\n    Grid: tui_components_grid_Grid__WEBPACK_IMPORTED_MODULE_1___default.a,\n    GridItem: tui_components_grid_GridItem__WEBPACK_IMPORTED_MODULE_2___default.a,\n    HideShow: tui_components_collapsible_HideShow__WEBPACK_IMPORTED_MODULE_3___default.a,\n    LayoutReview: tui_components_layouts_LayoutOneColumn__WEBPACK_IMPORTED_MODULE_4___default.a,\n    LayoutSelect: totara_contentmarketplace_components_layouts_PageLayoutTwoColumn__WEBPACK_IMPORTED_MODULE_5___default.a,\n    SliderIcon: tui_components_icons_Slider__WEBPACK_IMPORTED_MODULE_6___default.a\n  },\n  props: {\n    loading: Boolean,\n    reviewTitle: {\n      type: String,\n      required: true\n    },\n    reviewingSelection: Boolean,\n    selectionTitle: {\n      type: String,\n      required: true\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=template&id=2d1cf99a&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?vue&type=template&id=2d1cf99a& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('Basket',{staticClass:\"tui-contentMarketplaceImportBasket\",attrs:{\"items\":_vm.selectedItems,\"wide-gap\":true},scopedSlots:_vm._u([{key:\"status\",fn:function(ref){\nvar empty = ref.empty;\nreturn [(!empty)?_c('Button',{attrs:{\"styleclass\":{ transparent: true },\"text\":_vm.$str('basket_clear_selection', 'totara_contentmarketplace')},on:{\"click\":function($event){return _vm.$emit('clear-selection')}}}):_vm._e()]}},{key:\"actions\",fn:function(ref){\nvar empty = ref.empty;\nreturn [(!_vm.viewingSelected)?[_c('div',{staticClass:\"tui-contentMarketplaceImportBasket__category\"},[_c('Select',{attrs:{\"id\":_vm.$id('categorySelect'),\"aria-label\":_vm.$str('basket_select_category', 'totara_contentmarketplace'),\"char-length\":\"15\",\"options\":_vm.options,\"required\":true,\"value\":_vm.selectedCategory},on:{\"input\":function($event){return _vm.$emit('category-change', $event)}}})],1),_vm._v(\" \"),_c('Button',{attrs:{\"disabled\":empty || !_vm.selectedCategory,\"styleclass\":{ primary: true },\"text\":_vm.$str('basket_go_to_review', 'totara_contentmarketplace')},on:{\"click\":function($event){return _vm.$emit('reviewing-selection', true)}}})]:[_c('Button',{attrs:{\"styleclass\":{ transparent: true },\"text\":_vm.$str('basket_back_to_catalogue', 'totara_contentmarketplace')},on:{\"click\":function($event){return _vm.$emit('reviewing-selection', false)}}}),_vm._v(\" \"),_c('Button',{attrs:{\"disabled\":empty || _vm.creatingContent,\"styleclass\":{ primary: true },\"text\":_vm.$str('basket_create_courses', 'totara_contentmarketplace')},on:{\"click\":function($event){return _vm.$emit('create-courses')}}})]]}}])})}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/basket/ImportBasket.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=template&id=a512d6a6&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?vue&type=template&id=a512d6a6& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplaceImportCountAndFilters\",attrs:{\"aria-live\":\"polite\"}},[(_vm.activeFilters)?[_vm._v(\"\\n    \"+_vm._s(_vm.$str('item_count_and_filters', 'totara_contentmarketplace', {\n        count: _vm.formattedCount,\n        filters: _vm.activeFilters,\n      }))+\"\\n  \")]:[_vm._v(\"\\n    \"+_vm._s(_vm.$str('item_count', 'totara_contentmarketplace', {\n        count: _vm.formattedCount,\n      }))+\"\\n  \")]],2)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/count/ImportCountAndFilters.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=template&id=7bd74f40&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?vue&type=template&id=7bd74f40& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplaceImportSortFilter\"},[_c('Label',{staticClass:\"tui-contentMarketplaceImportSortFilter__label\",attrs:{\"for-id\":_vm.$id('sortBy'),\"label\":_vm.$str('filter_sort_by', 'totara_contentmarketplace')}}),_vm._v(\" \"),_c('Select',{attrs:{\"id\":_vm.$id('sortBy'),\"char-length\":\"10\",\"options\":_vm.options,\"value\":_vm.sortBy},on:{\"input\":function($event){return _vm.$emit('filter-change', $event)}}})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/filters/ImportSortFilter.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?vue&type=template&id=425e36a2& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplacePageLayoutTwoColumn\",class:{\n    'tui-contentMarketplacePageLayoutTwoColumn--flush': _vm.flush,\n  }},[_vm._t(\"feedback-banner\"),_vm._v(\" \"),_vm._t(\"user-overview\"),_vm._v(\" \"),_c('div',{staticClass:\"tui-contentMarketplacePageLayoutTwoColumn__heading\"},[_vm._t(\"content-nav\"),_vm._v(\" \"),_c('PageHeading',{attrs:{\"title\":_vm.title},scopedSlots:_vm._u([{key:\"buttons\",fn:function(){return [_vm._t(\"header-buttons\")]},proxy:true}],null,true)})],2),_vm._v(\" \"),_vm._t(\"pre-body\"),_vm._v(\" \"),_c('Loader',{staticClass:\"tui-contentMarketplacePageLayoutTwoColumn__body\",attrs:{\"loading\":_vm.loading}},[_c('Grid',{attrs:{\"stack-at\":_vm.stackAt}},[_c('GridItem',{attrs:{\"units\":3}},[_vm._t(\"left-content\")],2),_vm._v(\" \"),_c('GridItem',{attrs:{\"units\":9}},[_c('Loader',{attrs:{\"loading\":_vm.loadingRight}},[_vm._t(\"right-content\")],2)],1)],1)],1),_vm._v(\" \"),_vm._t(\"modals\")],2)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/layouts/PageLayoutTwoColumn.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?vue&type=template&id=1fa477ba& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplaceImportReviewPaging\"},[(!_vm.lastPage)?_c('Button',{attrs:{\"text\":_vm.$str('load_more', 'totara_contentmarketplace')},on:{\"click\":function($event){return _vm.$emit('next-page')}}}):_vm._e()],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportReviewLoadMore.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?vue&type=template&id=b0b04a3e& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.totalItems)?_c('Paging',{staticClass:\"tui-contentMarketplaceImportSelectionPaging\",attrs:{\"items-per-page\":_vm.itemsPerPage,\"page\":_vm.currentPage,\"total-items\":_vm.totalItems},on:{\"count-change\":function($event){return _vm.$emit('items-per-page-change', $event)},\"page-change\":function($event){return _vm.$emit('page-change', $event)}}}):_vm._e()}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/paging/ImportSelectionPaging.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=template&id=c0992cd0&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?vue&type=template&id=c0992cd0& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplaceImportReviewTable\"},[_c('SelectTable',{attrs:{\"border-bottom-hidden\":true,\"data\":_vm.items,\"hover-off\":true,\"no-label-offset\":true,\"row-label-key\":_vm.rowLabelKey,\"select-all-enabled\":true,\"selected-highlight-off\":true,\"value\":_vm.selectedItems},on:{\"input\":function($event){return _vm.$emit('update', $event)}},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',{attrs:{\"size\":\"12\",\"valign\":\"center\"}})]},proxy:true},{key:\"row\",fn:function(ref){\nvar checked = ref.checked;\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"size\":\"12\"}},[_vm._t(\"row\",null,{\"checked\":checked,\"row\":row})],2)]}}],null,true)})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportReviewTable.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=template&id=24293760&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?vue&type=template&id=24293760& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplaceImportSelectionTable\"},[_c('SelectTable',{attrs:{\"border-bottom-hidden\":true,\"data\":_vm.items,\"hover-off\":true,\"large-check-box\":true,\"no-label-offset\":true,\"row-label-key\":_vm.rowLabelKey,\"select-all-enabled\":true,\"selected-highlight-off\":true,\"value\":_vm.selectedItems},on:{\"input\":function($event){return _vm.$emit('update', $event)}},scopedSlots:_vm._u([{key:\"header-row\",fn:function(){return [_c('HeaderCell',{attrs:{\"size\":\"12\",\"valign\":\"center\"}})]},proxy:true},{key:\"row\",fn:function(ref){\nvar row = ref.row;\nreturn [_c('Cell',{attrs:{\"size\":\"12\"}},[_vm._t(\"row\",null,{\"row\":row})],2)]}}],null,true)})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/components/tables/ImportSelectionTable.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=template&id=6865c015&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?vue&type=template&id=6865c015& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-contentMarketplaceImport\"},[_c('div',{staticClass:\"sr-only\",attrs:{\"aria-atomic\":\"true\",\"aria-live\":\"polite\",\"role\":\"status\"}},[(_vm.reviewingSelection)?[_vm._v(\"\\n      \"+_vm._s(_vm.$str('a11y_import_page_reviewing', 'totara_contentmarketplace'))+\"\\n    \")]:[_vm._v(\"\\n      \"+_vm._s(_vm.$str('a11y_import_page_selecting', 'totara_contentmarketplace'))+\"\\n    \")]],2),_vm._v(\" \"),(!_vm.reviewingSelection)?_c('LayoutSelect',{attrs:{\"title\":_vm.selectionTitle,\"loading-right\":_vm.loading,\"stack-at\":1150},scopedSlots:_vm._u([{key:\"content-nav\",fn:function(){return [_vm._t(\"content-nav\")]},proxy:true},{key:\"pre-body\",fn:function(){return [_c('Grid',{attrs:{\"stack-at\":1050}},[_c('GridItem',{attrs:{\"units\":6}}),_vm._v(\" \"),_c('GridItem',{attrs:{\"units\":6}},[_vm._t(\"basket\")],2)],1)]},proxy:true},{key:\"left-content\",fn:function(){return [_c('aside',{staticClass:\"tui-contentMarketplaceImport__filters\"},[_c('HideShow',{attrs:{\"aria-region-label\":_vm.$str('a11y_filter_panel', 'totara_contentmarketplace'),\"hide-content-text\":_vm.$str('hide_filters', 'totara_contentmarketplace'),\"mobile-only\":true,\"show-content-text\":_vm.$str('show_filters', 'totara_contentmarketplace'),\"sticky\":true},scopedSlots:_vm._u([{key:\"trigger\",fn:function(ref){\nvar controls = ref.controls;\nvar expanded = ref.expanded;\nvar text = ref.text;\nvar toggleContent = ref.toggleContent;\nreturn [_c('ButtonIcon',{staticClass:\"tui-contentMarketplaceImport__filters-toggle\",class:{\n                'tui-contentMarketplaceImport__filters-toggleExpanded': expanded,\n              },attrs:{\"aria-controls\":controls,\"aria-label\":false,\"styleclass\":{ transparent: true },\"text\":text},on:{\"click\":toggleContent}},[_c('SliderIcon')],1)]}},{key:\"content\",fn:function(){return [_c('div',{staticClass:\"tui-contentMarketplaceImport__filters-content\"},[_vm._t(\"primary-filter\"),_vm._v(\" \"),_vm._t(\"filters\",null,{\"contentId\":\"contentMarketplaceImportBody\"})],2)]},proxy:true}],null,true)})],1)]},proxy:true},{key:\"right-content\",fn:function(){return [_c('div',{staticClass:\"tui-contentMarketplaceImport__body\",attrs:{\"id\":\"contentMarketplaceImportBody\",\"tabindex\":\"-1\"}},[_c('Grid',{attrs:{\"stack-at\":600}},[_c('GridItem',{staticClass:\"tui-contentMarketplaceImport__summary-gridItem\",attrs:{\"units\":8}},[_vm._t(\"summary-count\")],2),_vm._v(\" \"),_c('GridItem',{staticClass:\"tui-contentMarketplaceImport__summary-gridItem\",attrs:{\"units\":4}},[_vm._t(\"summary-sort\")],2)],1),_vm._v(\" \"),_vm._t(\"select-table\")],2)]},proxy:true}],null,true)}):_c('LayoutReview',{attrs:{\"title\":_vm.reviewTitle,\"loading\":_vm.loading},scopedSlots:_vm._u([{key:\"content-nav\",fn:function(){return [_vm._t(\"content-nav\")]},proxy:true},{key:\"pre-body\",fn:function(){return [_c('Grid',{attrs:{\"stack-at\":1150}},[_c('GridItem',{attrs:{\"units\":6}}),_vm._v(\" \"),_c('GridItem',{attrs:{\"units\":6}},[_vm._t(\"basket\")],2)],1)]},proxy:true},{key:\"content\",fn:function(){return [_c('div',{staticClass:\"tui-contentMarketplaceImport__body\"},[_vm._t(\"review-table\")],2)]},proxy:true}],null,true)})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/totara_contentmarketplace/src/pages/CatalogImportLayout.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

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

/***/ "totara_contentmarketplace/components/layouts/PageLayoutTwoColumn":
/*!****************************************************************************************************!*\
  !*** external "tui.require(\"totara_contentmarketplace/components/layouts/PageLayoutTwoColumn\")" ***!
  \****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"totara_contentmarketplace/components/layouts/PageLayoutTwoColumn\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22totara_contentmarketplace/components/layouts/PageLayoutTwoColumn\\%22)%22?");

/***/ }),

/***/ "tui/components/basket/Basket":
/*!****************************************************************!*\
  !*** external "tui.require(\"tui/components/basket/Basket\")" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/basket/Basket\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/basket/Basket\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/Button":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/Button\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/Button\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/Button\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/ButtonIcon":
/*!*********************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/ButtonIcon\")" ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/ButtonIcon\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/ButtonIcon\\%22)%22?");

/***/ }),

/***/ "tui/components/collapsible/HideShow":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/collapsible/HideShow\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/collapsible/HideShow\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/collapsible/HideShow\\%22)%22?");

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

/***/ "tui/components/datatable/SelectTable":
/*!************************************************************************!*\
  !*** external "tui.require(\"tui/components/datatable/SelectTable\")" ***!
  \************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/datatable/SelectTable\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/datatable/SelectTable\\%22)%22?");

/***/ }),

/***/ "tui/components/form/Label":
/*!*************************************************************!*\
  !*** external "tui.require(\"tui/components/form/Label\")" ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/form/Label\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/form/Label\\%22)%22?");

/***/ }),

/***/ "tui/components/form/Select":
/*!**************************************************************!*\
  !*** external "tui.require(\"tui/components/form/Select\")" ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/form/Select\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/form/Select\\%22)%22?");

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

/***/ "tui/components/icons/Slider":
/*!***************************************************************!*\
  !*** external "tui.require(\"tui/components/icons/Slider\")" ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/icons/Slider\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/icons/Slider\\%22)%22?");

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

/***/ "tui/components/paging/Paging":
/*!****************************************************************!*\
  !*** external "tui.require(\"tui/components/paging/Paging\")" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/paging/Paging\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/paging/Paging\\%22)%22?");

/***/ })

/******/ });