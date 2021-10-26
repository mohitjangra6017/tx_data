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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/weka_simple_multi_lang/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/weka_simple_multi_lang/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!***************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \***************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./MultiLangBlock\": \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue\",\n\t\"./MultiLangBlock.vue\": \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue\",\n\t\"./MultiLangBlockCollection\": \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue\",\n\t\"./MultiLangBlockCollection.vue\": \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue\",\n\t\"./form/SimpleMultiLangForm\": \"./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue\",\n\t\"./form/SimpleMultiLangForm.vue\": \"./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue\",\n\t\"./modal/EditSimpleMultiLangModal\": \"./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue\",\n\t\"./modal/EditSimpleMultiLangModal.vue\": \"./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/weka_simple_multi_lang/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/weka_simple_multi_lang/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue":
/*!***********************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _MultiLangBlock_vue_vue_type_template_id_d89cdffa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MultiLangBlock.vue?vue&type=template&id=d89cdffa& */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=template&id=d89cdffa&\");\n/* harmony import */ var _MultiLangBlock_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MultiLangBlock.vue?vue&type=script&lang=js& */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _MultiLangBlock_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MultiLangBlock.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _MultiLangBlock_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_MultiLangBlock_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _MultiLangBlock_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _MultiLangBlock_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _MultiLangBlock_vue_vue_type_template_id_d89cdffa___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _MultiLangBlock_vue_vue_type_template_id_d89cdffa___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _MultiLangBlock_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_MultiLangBlock_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**********************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlock_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlock_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_MultiLangBlock_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./MultiLangBlock.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_MultiLangBlock_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=style&index=0&lang=scss&":
/*!*********************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=style&index=0&lang=scss& ***!
  \*********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=template&id=d89cdffa&":
/*!******************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=template&id=d89cdffa& ***!
  \******************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlock_vue_vue_type_template_id_d89cdffa___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MultiLangBlock.vue?vue&type=template&id=d89cdffa& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=template&id=d89cdffa&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlock_vue_vue_type_template_id_d89cdffa___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlock_vue_vue_type_template_id_d89cdffa___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue":
/*!*********************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _MultiLangBlockCollection_vue_vue_type_template_id_487ffbfe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe& */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe&\");\n/* harmony import */ var _MultiLangBlockCollection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MultiLangBlockCollection.vue?vue&type=script&lang=js& */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _MultiLangBlockCollection_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MultiLangBlockCollection.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _MultiLangBlockCollection_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_MultiLangBlockCollection_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _MultiLangBlockCollection_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _MultiLangBlockCollection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _MultiLangBlockCollection_vue_vue_type_template_id_487ffbfe___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _MultiLangBlockCollection_vue_vue_type_template_id_487ffbfe___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _MultiLangBlockCollection_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_MultiLangBlockCollection_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlockCollection_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlockCollection_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_MultiLangBlockCollection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./MultiLangBlockCollection.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_MultiLangBlockCollection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe& ***!
  \****************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlockCollection_vue_vue_type_template_id_487ffbfe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlockCollection_vue_vue_type_template_id_487ffbfe___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MultiLangBlockCollection_vue_vue_type_template_id_487ffbfe___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue":
/*!*********************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _SimpleMultiLangForm_vue_vue_type_template_id_56d35d4b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b& */ \"./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b&\");\n/* harmony import */ var _SimpleMultiLangForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SimpleMultiLangForm.vue?vue&type=script&lang=js& */ \"./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _SimpleMultiLangForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SimpleMultiLangForm.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _SimpleMultiLangForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_SimpleMultiLangForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _SimpleMultiLangForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _SimpleMultiLangForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _SimpleMultiLangForm_vue_vue_type_template_id_56d35d4b___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _SimpleMultiLangForm_vue_vue_type_template_id_56d35d4b___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _SimpleMultiLangForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_SimpleMultiLangForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!********************************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SimpleMultiLangForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SimpleMultiLangForm_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_SimpleMultiLangForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./SimpleMultiLangForm.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_SimpleMultiLangForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=style&index=0&lang=scss&":
/*!*******************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=style&index=0&lang=scss& ***!
  \*******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b& ***!
  \****************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SimpleMultiLangForm_vue_vue_type_template_id_56d35d4b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SimpleMultiLangForm_vue_vue_type_template_id_56d35d4b___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SimpleMultiLangForm_vue_vue_type_template_id_56d35d4b___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue":
/*!***************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _EditSimpleMultiLangModal_vue_vue_type_template_id_6e0751c1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1& */ \"./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1&\");\n/* harmony import */ var _EditSimpleMultiLangModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditSimpleMultiLangModal.vue?vue&type=script&lang=js& */ \"./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _EditSimpleMultiLangModal_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _EditSimpleMultiLangModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _EditSimpleMultiLangModal_vue_vue_type_template_id_6e0751c1___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _EditSimpleMultiLangModal_vue_vue_type_template_id_6e0751c1___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _EditSimpleMultiLangModal_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_EditSimpleMultiLangModal_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!**************************************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \**************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditSimpleMultiLangModal_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditSimpleMultiLangModal_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_EditSimpleMultiLangModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./EditSimpleMultiLangModal.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_EditSimpleMultiLangModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1&":
/*!**********************************************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1& ***!
  \**********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditSimpleMultiLangModal_vue_vue_type_template_id_6e0751c1___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditSimpleMultiLangModal_vue_vue_type_template_id_6e0751c1___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditSimpleMultiLangModal_vue_vue_type_template_id_6e0751c1___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/js sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!*******************************************************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/js sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \*******************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./extension\": \"./client/component/weka_simple_multi_lang/src/js/extension.js\",\n\t\"./extension.js\": \"./client/component/weka_simple_multi_lang/src/js/extension.js\",\n\t\"./plugin\": \"./client/component/weka_simple_multi_lang/src/js/plugin.js\",\n\t\"./plugin.js\": \"./client/component/weka_simple_multi_lang/src/js/plugin.js\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/weka_simple_multi_lang/src/js sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/weka_simple_multi_lang/src/js_sync_^(?:(?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/js/extension.js":
/*!*********************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/js/extension.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var editor_weka_extensions_Base__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! editor_weka/extensions/Base */ \"editor_weka/extensions/Base\");\n/* harmony import */ var editor_weka_extensions_Base__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(editor_weka_extensions_Base__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var weka_simple_multi_lang_components_MultiLangBlock__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! weka_simple_multi_lang/components/MultiLangBlock */ \"weka_simple_multi_lang/components/MultiLangBlock\");\n/* harmony import */ var weka_simple_multi_lang_components_MultiLangBlock__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(weka_simple_multi_lang_components_MultiLangBlock__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var weka_simple_multi_lang_components_MultiLangBlockCollection__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! weka_simple_multi_lang/components/MultiLangBlockCollection */ \"weka_simple_multi_lang/components/MultiLangBlockCollection\");\n/* harmony import */ var weka_simple_multi_lang_components_MultiLangBlockCollection__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(weka_simple_multi_lang_components_MultiLangBlockCollection__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var editor_weka_toolbar__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! editor_weka/toolbar */ \"editor_weka/toolbar\");\n/* harmony import */ var editor_weka_toolbar__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(editor_weka_toolbar__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var tui_i18n__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! tui/i18n */ \"tui/i18n\");\n/* harmony import */ var tui_i18n__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(tui_i18n__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var tui_util__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! tui/util */ \"tui/util\");\n/* harmony import */ var tui_util__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(tui_util__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var tui_components_icons_MultiLang__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! tui/components/icons/MultiLang */ \"tui/components/icons/MultiLang\");\n/* harmony import */ var tui_components_icons_MultiLang__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(tui_components_icons_MultiLang__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _plugin__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./plugin */ \"./client/component/weka_simple_multi_lang/src/js/plugin.js\");\n/* harmony import */ var ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ext_prosemirror/state */ \"ext_prosemirror/state\");\n/* harmony import */ var ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_13__);\n/* harmony import */ var editor_weka_extensions_util__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! editor_weka/extensions/util */ \"editor_weka/extensions/util\");\n/* harmony import */ var editor_weka_extensions_util__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(editor_weka_extensions_util__WEBPACK_IMPORTED_MODULE_14__);\n/* harmony import */ var ext_prosemirror_view__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ext_prosemirror/view */ \"ext_prosemirror/view\");\n/* harmony import */ var ext_prosemirror_view__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(ext_prosemirror_view__WEBPACK_IMPORTED_MODULE_15__);\n\n\n\n\n\n\nfunction _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4___default()(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4___default()(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3___default()(this, result); }; }\n\nfunction _isNativeReflectConstruct() { if (typeof Reflect === \"undefined\" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === \"function\") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }\n\n/**\n * This file is part of Totara Enterprise Extensions.\n *\n * Copyright (C) 2021 onwards Totara Learning Solutions LTD\n *\n * Totara Enterprise Extensions is provided only to Totara\n * Learning Solutions LTD's customers and partners, pursuant to\n * the terms and conditions of a separate agreement with Totara\n * Learning Solutions LTD or its affiliate.\n *\n * If you do not have an agreement with Totara Learning Solutions\n * LTD, you may not access, use, modify, or distribute this software.\n * Please contact [licensing@totaralearning.com] for more information.\n *\n * @author Kian Nguyen <kian.nguyen@totaralearning.com>\n * @module weka_simple_multi_lang\n */\n\n\n\n\n\n\n\n\n\n // eslint-disable-next-line no-unused-vars\n\n\n\nvar WekaSimpleMultiLangExtension = /*#__PURE__*/function (_BaseExtension) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(WekaSimpleMultiLangExtension, _BaseExtension);\n\n  var _super = _createSuper(WekaSimpleMultiLangExtension);\n\n  function WekaSimpleMultiLangExtension() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, WekaSimpleMultiLangExtension);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(WekaSimpleMultiLangExtension, [{\n    key: \"nodes\",\n    value: function nodes() {\n      var _this = this;\n\n      return {\n        weka_simple_multi_lang_lang_block: {\n          schema: {\n            atom: true,\n            selectable: false,\n            isolating: true,\n            group: 'weka_simple_multi_lang_lang_blocks',\n            draggable: false,\n            content: '(paragraph|heading)*',\n            allowGapCursor: false,\n            attrs: {\n              lang: {\n                \"default\": undefined\n              },\n              siblings_count: {\n                \"default\": 1\n              }\n            },\n            toDOM: function toDOM(node) {\n              return ['div', {\n                \"class\": 'tui-wekaMultiLangBlock',\n                'data-attrs': JSON.stringify({\n                  lang: node.attrs.lang,\n                  siblings_count: node.attrs.siblings_count\n                })\n              }, 0];\n            },\n            parseDOM: [{\n              tag: 'div.tui-wekaMultiLangBlock',\n              getAttrs: editor_weka_extensions_util__WEBPACK_IMPORTED_MODULE_14__[\"getJsonAttrs\"]\n            }]\n          },\n          component: weka_simple_multi_lang_components_MultiLangBlock__WEBPACK_IMPORTED_MODULE_6___default.a,\n          componentContext: {\n            removeSelf: this._removeLangBlock.bind(this),\n            updateSelf: this._updateLangBlock.bind(this),\n            getCompact: function getCompact() {\n              return _this.options.compact || false;\n            },\n            getPlaceholderResolverClassName: function getPlaceholderResolverClassName() {\n              return _this.options.placeholder_resolver_class_name || null;\n            }\n          }\n        },\n\n        /**\n         * A collection block\n         */\n        weka_simple_multi_lang_lang_blocks: {\n          schema: {\n            atom: true,\n            draggable: false,\n            selectable: true,\n            isolating: true,\n            group: 'block',\n            content: 'weka_simple_multi_lang_lang_block+',\n            allowGapCursor: false,\n            toDOM: function toDOM() {\n              return ['div', {\n                \"class\": 'tui-wekaMultiLangBlockCollection'\n              }, 0];\n            },\n            parseDOM: [{\n              tag: 'div.tui-wekaMultiLangBlockCollection'\n            }]\n          },\n          component: weka_simple_multi_lang_components_MultiLangBlockCollection__WEBPACK_IMPORTED_MODULE_7___default.a,\n          componentContext: {\n            insertNewLangBlock: this._insertNewLangBlock.bind(this)\n          }\n        }\n      };\n    }\n  }, {\n    key: \"toolbarItems\",\n    value: function toolbarItems() {\n      if (!this.options.is_active) {\n        // Do not show the toolbar item, if the multi lang filter is\n        // not enabled for the system.\n        return [];\n      }\n\n      return [new editor_weka_toolbar__WEBPACK_IMPORTED_MODULE_8__[\"ToolbarItem\"]({\n        group: 'embeds',\n        label: Object(tui_i18n__WEBPACK_IMPORTED_MODULE_9__[\"langString\"])('multi_lang', 'weka_simple_multi_lang'),\n        iconComponent: tui_components_icons_MultiLang__WEBPACK_IMPORTED_MODULE_11___default.a,\n        execute: this._createCollectionBlock.bind(this)\n      })];\n    }\n  }, {\n    key: \"plugins\",\n    value: function plugins() {\n      return [Object(_plugin__WEBPACK_IMPORTED_MODULE_12__[\"default\"])({\n        handleKeyDown: this._handleKeyDown.bind(this)\n      })];\n    }\n  }, {\n    key: \"keymap\",\n    value: function keymap(bind) {\n      if (!this.options.is_active) {\n        // Remove the key binding, if the multi lang filter is not enabled for the system.\n        // This is to prevent the ability of creating a new collection block, via interface.\n        // However, it does not means that we are going to disable edit functionality.\n        // The reason that we are doing this is because of the node might be invalid if we disable it.\n        return;\n      }\n\n      bind('Ctrl-m', this._createCollectionBlock.bind(this));\n    }\n  }, {\n    key: \"loadSerializedVisitor\",\n    value: function loadSerializedVisitor() {\n      return {\n        weka_simple_multi_lang_lang_blocks: function weka_simple_multi_lang_lang_blocks(node) {\n          node.content.forEach(function (child) {\n            if (!child.attrs) child.attrs = {};\n            child.attrs.siblings_count = node.content.length;\n          });\n        }\n      };\n    }\n  }, {\n    key: \"saveSerializedVisitor\",\n    value: function saveSerializedVisitor() {\n      return {\n        weka_simple_multi_lang_lang_blocks: function weka_simple_multi_lang_lang_blocks(node) {\n          node.content.forEach(function (child) {\n            delete child.attrs.siblings_count;\n          });\n        }\n      };\n    }\n    /**\n     * @private\n     */\n\n  }, {\n    key: \"_createCollectionBlock\",\n    value: function _createCollectionBlock() {\n      var _pick = Object(tui_util__WEBPACK_IMPORTED_MODULE_10__[\"pick\"])(this.editor.state.selection, ['from', 'to']),\n          from = _pick.from,\n          to = _pick.to;\n\n      this.editor.execute(function (state, dispatch) {\n        var transaction = state.tr;\n        transaction.replaceWith(from, to, [state.schema.node('weka_simple_multi_lang_lang_blocks', {}, [state.schema.node('weka_simple_multi_lang_lang_block', {\n          siblings_count: 2\n        }), state.schema.node('weka_simple_multi_lang_lang_block', {\n          siblings_count: 2\n        })])]);\n        dispatch(transaction);\n      });\n    }\n    /**\n     *\n     * @param {Function} getRange\n     * @private\n     */\n\n  }, {\n    key: \"_removeLangBlock\",\n    value: function _removeLangBlock(getRange) {\n      var _this2 = this;\n\n      var _getRange = getRange(),\n          from = _getRange.from; // We need to find out the parents of this current node, which is the collection node\n      // to check if the collection node only have two of this nodes or not.\n\n\n      var resolvedPosition = this.doc.resolve(from);\n      var parentResolvedPosition = this.doc.resolve(resolvedPosition.end());\n      var collectionNode = parentResolvedPosition.node();\n\n      if (collectionNode.type.name !== 'weka_simple_multi_lang_lang_blocks') {\n        console.error('[Weka] cannot resolve the position of parent collection node');\n        return;\n      }\n\n      if (collectionNode.content.content.length <= 2) {\n        console.warn('[Weka] cannot remove another single lang block due to the minimum requirement');\n        return;\n      }\n\n      this.editor.execute(function (state, dispatch) {\n        var schema = state.schema,\n            transaction = state.tr; // Convert the collection node to JSON data, so we can easily work with it.\n\n        collectionNode = collectionNode.toJSON(); // Remove one item by the index of it.\n\n        collectionNode.content = collectionNode.content.filter(function (node, index) {\n          return index !== resolvedPosition.index();\n        });\n        var currentTotal = collectionNode.content.length;\n        collectionNode.content = collectionNode.content.map(function (node) {\n          node = Object.assign({}, node);\n          node.attrs = Object.assign({}, node.attrs, {\n            siblings_count: currentTotal\n          });\n          return node;\n        });\n        transaction.replaceWith(parentResolvedPosition.before(), parentResolvedPosition.after(), schema.nodeFromJSON(collectionNode));\n        dispatch(transaction);\n\n        _this2.editor.view.focus();\n      });\n    }\n    /**\n     * @param {Object} attrs\n     * @param {Array} content\n     * @param {Function} getRange\n     * @private\n     */\n\n  }, {\n    key: \"_updateLangBlock\",\n    value: function _updateLangBlock(_ref, getRange) {\n      var attrs = _ref.attrs,\n          content = _ref.content;\n\n      var _getRange2 = getRange(),\n          from = _getRange2.from,\n          to = _getRange2.to;\n\n      this.editor.execute(function (state, dispatch) {\n        var transaction = state.tr;\n        var contentNodes = content.map(function (jsonNode) {\n          return state.schema.nodeFromJSON(jsonNode);\n        });\n        dispatch(transaction.replaceWith(from, to, state.schema.node('weka_simple_multi_lang_lang_block', attrs, contentNodes)));\n      });\n      this.editor.view.focus();\n    }\n    /**\n     *\n     * @param {Function} getRange\n     * @private\n     */\n\n  }, {\n    key: \"_insertNewLangBlock\",\n    value: function _insertNewLangBlock(getRange) {\n      var _getRange3 = getRange(),\n          to = _getRange3.to; // Resolve the collection block, which we need to step inside by 1.\n\n\n      var resolvedPosition = this.editor.state.doc.resolve(to - 1);\n      var collectionNode = resolvedPosition.node();\n\n      if (collectionNode.type.name !== 'weka_simple_multi_lang_lang_blocks') {\n        console.warn(\"Unable to resolve node 'weka_simple_multi_lang_lang_blocks' from the position \".concat(to));\n        return;\n      } // Add new item to content of collection node, but also updating the siblings counter\n      // of the child within it.\n\n\n      collectionNode = collectionNode.toJSON();\n      var currentTotal = collectionNode.content.length;\n      collectionNode.content = collectionNode.content.map(function (node) {\n        node = Object.assign({}, node);\n        node.attrs = Object.assign({}, node.attrs, {\n          siblings_count: currentTotal + 1\n        });\n        return node;\n      });\n      this.editor.execute(function (state, dispatch) {\n        var transaction = state.tr,\n            schema = state.schema;\n        transaction.replaceWith(resolvedPosition.before(), resolvedPosition.after(), schema.nodeFromJSON(collectionNode)); // Insert a new block\n\n        transaction.insert(resolvedPosition.end(), schema.node('weka_simple_multi_lang_lang_block', {\n          siblings_count: currentTotal + 1\n        }));\n        dispatch(transaction);\n      });\n      this.editor.view.focus();\n    }\n    /**\n     *\n     * @param {EditorView} view\n     * @param {KeyboardEvent} event\n     *\n     * @return {Boolean}\n     * @private\n     */\n\n  }, {\n    key: \"_handleKeyDown\",\n    value: function _handleKeyDown(view, event) {\n      switch (event.key) {\n        case 'Enter':\n          return this._createNewLine(view);\n\n        case 'Backspace':\n        case 'Delete':\n          return this._handleRemoveCollectionBlocKFromBackspace(view);\n\n        default:\n          // eslint-disable-next-line no-case-declarations\n          var resolvedPos = view.state.tr.selection.$from,\n              currentNode = resolvedPos.node();\n\n          if (currentNode && (currentNode.type.name === 'weka_simple_multi_lang_lang_blocks' || currentNode.type.name === 'weka_simple_multi_lang_lang_block')) {\n            // Disable every other keys within the collection block.\n            return true;\n          } // Otherwise let prose mirror handle it.\n\n\n          return false;\n      }\n    }\n    /**\n     *\n     * @param {EditorView} view\n     * @return {Boolean}\n     * @private\n     */\n\n  }, {\n    key: \"_createNewLine\",\n    value: function _createNewLine(view) {\n      var _this3 = this;\n\n      var from = view.state.tr.selection.$from,\n          node = from.node();\n\n      var getInsertionFrom = function getInsertionFrom() {\n        switch (node.type.name) {\n          case 'weka_simple_multi_lang_lang_blocks':\n            return from.after();\n\n          case 'weka_simple_multi_lang_lang_block':\n            // Get the parent position\n            // eslint-disable-next-line no-case-declarations\n            var resolvedPosition = _this3.doc.resolve(from.before());\n\n            return resolvedPosition.after();\n\n          case 'paragraph':\n          case 'heading':\n            // We need to check if the the current paragraph node is within the lang_block or not.i\n            // eslint-disable-next-line no-case-declarations\n            var parentNode = from.node(from.depth - 1);\n\n            if (parentNode.type.name === 'weka_simple_multi_lang_lang_block') {\n              var blockPosition = view.state.doc.resolve(from.before()),\n                  collectionPosition = view.state.doc.resolve(blockPosition.before());\n              return collectionPosition.after();\n            }\n\n            return null;\n\n          default:\n            return null;\n        }\n      };\n\n      var insertPoint = getInsertionFrom();\n\n      if (insertPoint !== null) {\n        this.editor.execute(function (state, dispatch) {\n          var transaction = state.tr; // Insert the new paragraph node.\n\n          transaction.insert(insertPoint, state.schema.nodes.paragraph.createAndFill()); // Move the cursor to this newly created paragraph node.\n\n          transaction.setSelection(new ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_13__[\"TextSelection\"](transaction.doc.resolve(insertPoint + 1)));\n          dispatch(transaction);\n        });\n        return true;\n      }\n\n      return false;\n    }\n    /**\n     *\n     * @param {EditorView} view\n     * @return {Boolean}\n     *\n     * @private\n     */\n\n  }, {\n    key: \"_handleRemoveCollectionBlocKFromBackspace\",\n    value: function _handleRemoveCollectionBlocKFromBackspace(view) {\n      var _this4 = this;\n\n      var resolvedPosition = view.state.tr.selection.$from,\n          currentNode = resolvedPosition.node();\n\n      var deleteBlock = function deleteBlock(_ref2) {\n        var from = _ref2.from,\n            to = _ref2.to;\n\n        _this4.editor.execute(function (state, dispatch) {\n          var transaction = state.tr;\n          transaction[\"delete\"](from, to);\n          dispatch(transaction);\n        });\n      };\n\n      if (currentNode.type.name === 'weka_simple_multi_lang_lang_blocks') {\n        // Cursor in the collection block or single block.\n        deleteBlock({\n          from: resolvedPosition.before(),\n          to: resolvedPosition.after()\n        });\n        return true;\n      } else if (currentNode.type.name === 'weka_simple_multi_lang_lang_block') {\n        // If the selection is within the single block, then we do not remove the whole collection block.\n        return true;\n      } else if (currentNode.type.name === 'paragraph') {\n        // Check its parent node.\n        var parentNode = resolvedPosition.node(resolvedPosition.depth - 1);\n\n        if (parentNode.type.name === 'weka_simple_multi_lang_lang_block') {\n          // Nope, we do not allow inline editing.\n          return true;\n        }\n      }\n\n      return false;\n    }\n  }]);\n\n  return WekaSimpleMultiLangExtension;\n}(editor_weka_extensions_Base__WEBPACK_IMPORTED_MODULE_5___default.a);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (options) {\n  return new WekaSimpleMultiLangExtension(options);\n});\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/js/extension.js?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/js/plugin.js":
/*!******************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/js/plugin.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ext_prosemirror/state */ \"ext_prosemirror/state\");\n/* harmony import */ var ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_0__);\n/**\n * This file is part of Totara Enterprise Extensions.\n *\n * Copyright (C) 2021 onwards Totara Learning Solutions LTD\n *\n * Totara Enterprise Extensions is provided only to Totara\n * Learning Solutions LTD's customers and partners, pursuant to\n * the terms and conditions of a separate agreement with Totara\n * Learning Solutions LTD or its affiliate.\n *\n * If you do not have an agreement with Totara Learning Solutions\n * LTD, you may not access, use, modify, or distribute this software.\n * Please contact [licensing@totaralearning.com] for more information.\n *\n * @author Kian Nguyen <kian.nguyen@totaralearning.com>\n * @module weka_simple_multi_lang\n */\n\n/**\n * @param {Function}  handleKeyDown\n * @return {Plugin}\n */\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (_ref) {\n  var handleKeyDown = _ref.handleKeyDown;\n  var pluginKey = new ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_0__[\"PluginKey\"]('weka_simple_multi_lang');\n  return new ext_prosemirror_state__WEBPACK_IMPORTED_MODULE_0__[\"Plugin\"]({\n    key: pluginKey,\n    props: {\n      handleKeyDown: handleKeyDown\n    }\n  });\n});\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/js/plugin.js?");

/***/ }),

/***/ "./client/component/weka_simple_multi_lang/src/tui.json":
/*!**************************************************************!*\
  !*** ./client/component/weka_simple_multi_lang/src/tui.json ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"weka_simple_multi_lang\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"weka_simple_multi_lang\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"weka_simple_multi_lang\")\ntui._bundle.addModulesFromContext(\"weka_simple_multi_lang\", __webpack_require__(\"./client/component/weka_simple_multi_lang/src/js sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\ntui._bundle.addModulesFromContext(\"weka_simple_multi_lang/components\", __webpack_require__(\"./client/component/weka_simple_multi_lang/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"weka_simple_multi_lang\": [\n    \"edit_language_x\",\n    \"remove_language_x\",\n    \"unspecified\",\n    \"language_label\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"weka_simple_multi_lang\": [\n    \"add_new\",\n    \"remove_collection_block\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"weka_simple_multi_lang\": [\n    \"lang_code\",\n    \"content\",\n    \"content_help\",\n    \"lang_code_help\"\n  ],\n  \"totara_core\": [\n    \"save\"\n  ],\n  \"moodle\": [\n    \"required\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"weka_simple_multi_lang\": [\n    \"edit_language\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/assertThisInitialized.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _assertThisInitialized(self) {\n  if (self === void 0) {\n    throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\");\n  }\n\n  return self;\n}\n\nmodule.exports = _assertThisInitialized;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/assertThisInitialized.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/classCallCheck.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _classCallCheck(instance, Constructor) {\n  if (!(instance instanceof Constructor)) {\n    throw new TypeError(\"Cannot call a class as a function\");\n  }\n}\n\nmodule.exports = _classCallCheck;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/classCallCheck.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/createClass.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _defineProperties(target, props) {\n  for (var i = 0; i < props.length; i++) {\n    var descriptor = props[i];\n    descriptor.enumerable = descriptor.enumerable || false;\n    descriptor.configurable = true;\n    if (\"value\" in descriptor) descriptor.writable = true;\n    Object.defineProperty(target, descriptor.key, descriptor);\n  }\n}\n\nfunction _createClass(Constructor, protoProps, staticProps) {\n  if (protoProps) _defineProperties(Constructor.prototype, protoProps);\n  if (staticProps) _defineProperties(Constructor, staticProps);\n  return Constructor;\n}\n\nmodule.exports = _createClass;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/createClass.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/getPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/getPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _getPrototypeOf(o) {\n  module.exports = _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {\n    return o.__proto__ || Object.getPrototypeOf(o);\n  };\n  module.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n  return _getPrototypeOf(o);\n}\n\nmodule.exports = _getPrototypeOf;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/getPrototypeOf.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/inherits.js":
/*!*********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/inherits.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var setPrototypeOf = __webpack_require__(/*! ./setPrototypeOf.js */ \"./node_modules/@babel/runtime/helpers/setPrototypeOf.js\");\n\nfunction _inherits(subClass, superClass) {\n  if (typeof superClass !== \"function\" && superClass !== null) {\n    throw new TypeError(\"Super expression must either be null or a function\");\n  }\n\n  subClass.prototype = Object.create(superClass && superClass.prototype, {\n    constructor: {\n      value: subClass,\n      writable: true,\n      configurable: true\n    }\n  });\n  if (superClass) setPrototypeOf(subClass, superClass);\n}\n\nmodule.exports = _inherits;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/inherits.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var _typeof = __webpack_require__(/*! @babel/runtime/helpers/typeof */ \"./node_modules/@babel/runtime/helpers/typeof.js\")[\"default\"];\n\nvar assertThisInitialized = __webpack_require__(/*! ./assertThisInitialized.js */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n\nfunction _possibleConstructorReturn(self, call) {\n  if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) {\n    return call;\n  }\n\n  return assertThisInitialized(self);\n}\n\nmodule.exports = _possibleConstructorReturn;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/setPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/setPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _setPrototypeOf(o, p) {\n  module.exports = _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {\n    o.__proto__ = p;\n    return o;\n  };\n\n  module.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n  return _setPrototypeOf(o, p);\n}\n\nmodule.exports = _setPrototypeOf;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/setPrototypeOf.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(obj) {\n  \"@babel/helpers - typeof\";\n\n  if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") {\n    module.exports = _typeof = function _typeof(obj) {\n      return typeof obj;\n    };\n\n    module.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n  } else {\n    module.exports = _typeof = function _typeof(obj) {\n      return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj;\n    };\n\n    module.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n  }\n\n  return _typeof(obj);\n}\n\nmodule.exports = _typeof;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/typeof.js?");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! editor_weka/components/nodes/BaseNode */ \"editor_weka/components/nodes/BaseNode\");\n/* harmony import */ var editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/buttons/ButtonIcon */ \"tui/components/buttons/ButtonIcon\");\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_icons_Edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/icons/Edit */ \"tui/components/icons/Edit\");\n/* harmony import */ var tui_components_icons_Edit__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_icons_Edit__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_icons_Delete__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/icons/Delete */ \"tui/components/icons/Delete\");\n/* harmony import */ var tui_components_icons_Delete__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_icons_Delete__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_modal_ModalPresenter__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/modal/ModalPresenter */ \"tui/components/modal/ModalPresenter\");\n/* harmony import */ var tui_components_modal_ModalPresenter__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_modal_ModalPresenter__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var weka_simple_multi_lang_components_modal_EditSimpleMultiLangModal__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! weka_simple_multi_lang/components/modal/EditSimpleMultiLangModal */ \"weka_simple_multi_lang/components/modal/EditSimpleMultiLangModal\");\n/* harmony import */ var weka_simple_multi_lang_components_modal_EditSimpleMultiLangModal__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(weka_simple_multi_lang_components_modal_EditSimpleMultiLangModal__WEBPACK_IMPORTED_MODULE_5__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ButtonIcon: tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1___default.a,\n    Edit: tui_components_icons_Edit__WEBPACK_IMPORTED_MODULE_2___default.a,\n    Delete: tui_components_icons_Delete__WEBPACK_IMPORTED_MODULE_3___default.a,\n    ModalPresenter: tui_components_modal_ModalPresenter__WEBPACK_IMPORTED_MODULE_4___default.a,\n    EditMultiLangModal: weka_simple_multi_lang_components_modal_EditSimpleMultiLangModal__WEBPACK_IMPORTED_MODULE_5___default.a\n  },\n  \"extends\": editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0___default.a,\n  data: function data() {\n    return {\n      editModal: false\n    };\n  },\n  computed: {\n    language: function language() {\n      return this.attrs.lang;\n    },\n\n    /**\n     * @return {Array}\n     */\n    langContentJson: function langContentJson() {\n      var jsonNodes = [];\n      this.node.content.forEach(function (node) {\n        return jsonNodes.push(node.toJSON());\n      });\n      return jsonNodes;\n    },\n    removable: function removable() {\n      if (this.editorDisabled) {\n        // Editor is disabled, so we cannot remove the node.\n        return false;\n      }\n\n      return this.attrs.siblings_count > 2;\n    },\n    editLanguageAriaLabel: function editLanguageAriaLabel() {\n      return this.$str('edit_language_x', 'weka_simple_multi_lang', this.language || this.$str('unspecified', 'weka_simple_multi_lang'));\n    },\n    removeLanguageAriaLabel: function removeLanguageAriaLabel() {\n      return this.$str('remove_language_x', 'weka_simple_multi_lang', this.language || this.$str('unspecified', 'weka_simple_multi_lang'));\n    },\n    editorCompact: function editorCompact() {\n      return this.context.getCompact();\n    },\n    placeholderResolverClass: function placeholderResolverClass() {\n      return this.context.getPlaceholderResolverClassName();\n    }\n  },\n  methods: {\n    /**\n     *\n     * @param {String} lang\n     * @param {Object[]} content\n     */\n    handleUpdateLangContent: function handleUpdateLangContent(_ref) {\n      var lang = _ref.lang,\n          content = _ref.content;\n      var parameters = {\n        attrs: {\n          lang: lang,\n          siblings_count: this.attrs.siblings_count\n        },\n        content: content\n      };\n      this.editModal = false;\n      this.context.updateSelf(parameters, this.getRange);\n    },\n\n    /**\n     * Executes self removing.\n     */\n    handleRemoving: function handleRemoving() {\n      this.context.removeSelf(this.getRange);\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! editor_weka/components/nodes/BaseNode */ \"editor_weka/components/nodes/BaseNode\");\n/* harmony import */ var editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/buttons/ButtonIcon */ \"tui/components/buttons/ButtonIcon\");\n/* harmony import */ var tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/icons/Add */ \"tui/components/icons/Add\");\n/* harmony import */ var tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_2__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    ButtonIcon: tui_components_buttons_ButtonIcon__WEBPACK_IMPORTED_MODULE_1___default.a,\n    Add: tui_components_icons_Add__WEBPACK_IMPORTED_MODULE_2___default.a\n  },\n  \"extends\": editor_weka_components_nodes_BaseNode__WEBPACK_IMPORTED_MODULE_0___default.a,\n  computed: {\n    isActionSpacingRequired: function isActionSpacingRequired() {\n      return this.node.content.content.length > 2;\n    }\n  },\n  methods: {\n    insertNewLangBlock: function insertNewLangBlock() {\n      this.context.insertNewLangBlock(this.getRange);\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_uniform_Uniform__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/uniform/Uniform */ \"tui/components/uniform/Uniform\");\n/* harmony import */ var tui_components_uniform_Uniform__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_uniform_Uniform__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var editor_weka_components_Weka__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! editor_weka/components/Weka */ \"editor_weka/components/Weka\");\n/* harmony import */ var editor_weka_components_Weka__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(editor_weka_components_Weka__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var editor_weka_WekaValue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! editor_weka/WekaValue */ \"editor_weka/WekaValue\");\n/* harmony import */ var editor_weka_WekaValue__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(editor_weka_WekaValue__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var tui_components_form_FormRow__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! tui/components/form/FormRow */ \"tui/components/form/FormRow\");\n/* harmony import */ var tui_components_form_FormRow__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(tui_components_form_FormRow__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var tui_components_uniform_FormText__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! tui/components/uniform/FormText */ \"tui/components/uniform/FormText\");\n/* harmony import */ var tui_components_uniform_FormText__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(tui_components_uniform_FormText__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var tui_components_uniform_FormField__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! tui/components/uniform/FormField */ \"tui/components/uniform/FormField\");\n/* harmony import */ var tui_components_uniform_FormField__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(tui_components_uniform_FormField__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var tui_components_buttons_ButtonGroup__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! tui/components/buttons/ButtonGroup */ \"tui/components/buttons/ButtonGroup\");\n/* harmony import */ var tui_components_buttons_ButtonGroup__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_ButtonGroup__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! tui/components/buttons/Button */ \"tui/components/buttons/Button\");\n/* harmony import */ var tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var tui_components_buttons_Cancel__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! tui/components/buttons/Cancel */ \"tui/components/buttons/Cancel\");\n/* harmony import */ var tui_components_buttons_Cancel__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(tui_components_buttons_Cancel__WEBPACK_IMPORTED_MODULE_8__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Uniform: tui_components_uniform_Uniform__WEBPACK_IMPORTED_MODULE_0___default.a,\n    FormRow: tui_components_form_FormRow__WEBPACK_IMPORTED_MODULE_3___default.a,\n    FormText: tui_components_uniform_FormText__WEBPACK_IMPORTED_MODULE_4___default.a,\n    FormField: tui_components_uniform_FormField__WEBPACK_IMPORTED_MODULE_5___default.a,\n    Weka: editor_weka_components_Weka__WEBPACK_IMPORTED_MODULE_1___default.a,\n    ButtonGroup: tui_components_buttons_ButtonGroup__WEBPACK_IMPORTED_MODULE_6___default.a,\n    Cancel: tui_components_buttons_Cancel__WEBPACK_IMPORTED_MODULE_8___default.a,\n    Button: tui_components_buttons_Button__WEBPACK_IMPORTED_MODULE_7___default.a\n  },\n  props: {\n    lang: {\n      type: String,\n      validator: function validator(lang) {\n        return lang.length <= 5;\n      }\n    },\n\n    /**\n     * Weka content value, which is the json encoded string of the\n     * list of paragaraph nodes only.\n     */\n    content: {\n      type: Array,\n      validator: function validator(nodes) {\n        return nodes.every(function (node) {\n          return node.type && (node.type === 'paragraph' || node.type === 'heading');\n        });\n      },\n      \"default\": function _default() {\n        return [];\n      }\n    },\n    editorCompact: Boolean,\n    editorExtraExtensions: {\n      type: Array,\n      \"default\": function _default() {\n        return [];\n      },\n      validator: function validator(extensions) {\n        return extensions.every(function (extension) {\n          return 'name' in extension;\n        });\n      }\n    }\n  },\n  data: function data() {\n    var wekaValue = null;\n\n    if (this.content.length) {\n      wekaValue = editor_weka_WekaValue__WEBPACK_IMPORTED_MODULE_2___default.a.fromDoc({\n        type: 'doc',\n        content: this.content\n      });\n    }\n\n    return {\n      formValue: {\n        lang: {\n          type: String,\n          value: this.lang\n        },\n        content: {\n          type: editor_weka_WekaValue__WEBPACK_IMPORTED_MODULE_2___default.a,\n          value: wekaValue\n        }\n      }\n    };\n  },\n  methods: {\n    submitForm: function submitForm(formValue) {\n      var submitParameters = {\n        lang: formValue.lang.value,\n        content: []\n      };\n\n      var _formValue$content$va = formValue.content.value.getDoc(),\n          content = _formValue$content$va.content;\n\n      if (content && content.length) {\n        submitParameters.content = content;\n      }\n\n      this.$emit('submit', submitParameters);\n    },\n\n    /**\n     *\n     * @param {WekaValue} content\n     */\n    validateContentEditor: function validateContentEditor(content) {\n      if (!content) {\n        return this.$str('required', 'moodle');\n      }\n\n      return content.isEmpty ? this.$str('required', 'moodle') : '';\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var weka_simple_multi_lang_components_form_SimpleMultiLangForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! weka_simple_multi_lang/components/form/SimpleMultiLangForm */ \"weka_simple_multi_lang/components/form/SimpleMultiLangForm\");\n/* harmony import */ var weka_simple_multi_lang_components_form_SimpleMultiLangForm__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(weka_simple_multi_lang_components_form_SimpleMultiLangForm__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_modal_Modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/modal/Modal */ \"tui/components/modal/Modal\");\n/* harmony import */ var tui_components_modal_Modal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_modal_Modal__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var tui_components_modal_ModalContent__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! tui/components/modal/ModalContent */ \"tui/components/modal/ModalContent\");\n/* harmony import */ var tui_components_modal_ModalContent__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(tui_components_modal_ModalContent__WEBPACK_IMPORTED_MODULE_2__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    MultiLangForm: weka_simple_multi_lang_components_form_SimpleMultiLangForm__WEBPACK_IMPORTED_MODULE_0___default.a,\n    Modal: tui_components_modal_Modal__WEBPACK_IMPORTED_MODULE_1___default.a,\n    ModalContent: tui_components_modal_ModalContent__WEBPACK_IMPORTED_MODULE_2___default.a\n  },\n  props: {\n    lang: String,\n    content: Array,\n    editorCompact: Boolean,\n    placeholderResolverClass: String\n  },\n  computed: {\n    editorExtraExtensions: function editorExtraExtensions() {\n      if (!this.placeholderResolverClass) {\n        return [];\n      }\n\n      return [{\n        name: 'weka_notification_placeholder_extension',\n        options: {\n          resolver_class_name: this.placeholderResolverClass\n        }\n      }];\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=template&id=d89cdffa&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?vue&type=template&id=d89cdffa& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-wekaMultiLangBlock\",attrs:{\"contenteditable\":\"false\"}},[_c('ModalPresenter',{attrs:{\"open\":_vm.editModal},on:{\"request-close\":function($event){_vm.editModal = false}}},[_c('EditMultiLangModal',{attrs:{\"lang\":_vm.language,\"content\":_vm.langContentJson,\"editor-compact\":_vm.editorCompact,\"placeholder-resolver-class\":_vm.placeholderResolverClass},on:{\"submit\":_vm.handleUpdateLangContent}})],1),_vm._v(\" \"),_c('div',{staticClass:\"tui-wekaMultiLangBlock__container\"},[_c('div',{staticClass:\"tui-wekaMultiLangBlock__wrapper\"},[_c('div',{staticClass:\"tui-wekaMultiLangBlock__languageWrapper\"},[_c('div',{staticClass:\"tui-wekaMultiLangBlock__language\"},[_vm._v(\"\\n          \"+_vm._s(_vm.$str(\n              'language_label',\n              'weka_simple_multi_lang',\n              _vm.language || _vm.$str('unspecified', 'weka_simple_multi_lang')\n            ))+\"\\n        \")]),_vm._v(\" \"),_c('div',{staticClass:\"tui-wekaMultiLangBlock__actions\"},[(!_vm.editorDisabled)?_c('ButtonIcon',{attrs:{\"aria-label\":_vm.editLanguageAriaLabel,\"styleclass\":{\n              transparent: true,\n              transparentNoPadding: true,\n            }},on:{\"click\":function($event){_vm.editModal = true}}},[_c('Edit',{attrs:{\"size\":100}})],1):_vm._e()],1)]),_vm._v(\" \"),_c('div',{ref:\"content\",staticClass:\"tui-wekaMultiLangBlock__texts\"})]),_vm._v(\" \"),(_vm.removable)?_c('ButtonIcon',{staticClass:\"tui-wekaMultiLangBlock__remove\",attrs:{\"aria-label\":_vm.removeLanguageAriaLabel,\"styleclass\":{\n        transparent: true,\n        transparentNoPadding: true,\n      }},on:{\"click\":_vm.handleRemoving}},[_c('Delete',{attrs:{\"size\":100}})],1):_vm._e()],1)],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlock.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?vue&type=template&id=487ffbfe& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-wekaMultiLangBlockCollection\"},[_c('div',{ref:\"content\",staticClass:\"tui-wekaMultiLangBlockCollection__content\"}),_vm._v(\" \"),_c('div',{staticClass:\"tui-wekaMultiLangBlockCollection__actions\",class:{\n      'tui-wekaMultiLangBlockCollection__actions--spacing': _vm.isActionSpacingRequired,\n    }},[(!_vm.editorDisabled)?_c('ButtonIcon',{attrs:{\"aria-label\":_vm.$str('add_new', 'weka_simple_multi_lang'),\"styleclass\":{\n        transparentNoPadding: true,\n      }},on:{\"click\":_vm.insertNewLangBlock}},[_c('Add',{attrs:{\"size\":300}})],1):_vm._e()],1)])}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/MultiLangBlockCollection.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?vue&type=template&id=56d35d4b& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('Uniform',{staticClass:\"tui-wekaSimpleMultiLangForm\",attrs:{\"initial-values\":_vm.formValue,\"input-width\":\"full\"},on:{\"submit\":_vm.submitForm}},[_c('FormRow',{attrs:{\"label\":_vm.$str('lang_code', 'weka_simple_multi_lang'),\"required\":true,\"helpmsg\":_vm.$str('lang_code_help', 'weka_simple_multi_lang')}},[[_c('FormText',{attrs:{\"name\":['lang', 'value'],\"maxlength\":5,\"char-length\":5,\"validations\":function (v) { return [v.required()]; }}})]],2),_vm._v(\" \"),_c('FormRow',{attrs:{\"label\":_vm.$str('content', 'weka_simple_multi_lang'),\"required\":true,\"helpmsg\":_vm.$str('content_help', 'weka_simple_multi_lang')}},[[_c('FormField',{attrs:{\"name\":['content', 'value'],\"char-length\":\"full\",\"validate\":_vm.validateContentEditor},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\nvar value = ref.value;\nvar update = ref.update;\nreturn [_c('Weka',{attrs:{\"value\":value,\"compact\":_vm.editorCompact,\"extra-extensions\":_vm.editorExtraExtensions,\"variant\":\"simple\"},on:{\"input\":update}})]}}])})]],2),_vm._v(\" \"),_c('ButtonGroup',{staticClass:\"tui-wekaSimpleMultiLangForm__buttonGroup\"},[[_c('Button',{attrs:{\"styleclass\":{ primary: true },\"text\":_vm.$str('save', 'totara_core'),\"aria-label\":_vm.$str('save', 'totara_core'),\"type\":\"submit\"}}),_vm._v(\" \"),_c('Cancel',{on:{\"click\":function($event){return _vm.$emit('cancel')}}})]],2)],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/form/SimpleMultiLangForm.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?vue&type=template&id=6e0751c1& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('Modal',{staticClass:\"tui-wekaEditSimpleMultiLangModal\",attrs:{\"size\":\"large\",\"dismissable\":{\n    esc: false,\n    backdropClick: false,\n  }}},[_c('ModalContent',{attrs:{\"title\":_vm.$str('edit_language', 'weka_simple_multi_lang'),\"close-button\":true}},[_c('MultiLangForm',{attrs:{\"lang\":_vm.lang,\"content\":_vm.content,\"editor-compact\":_vm.editorCompact,\"editor-extra-extensions\":_vm.editorExtraExtensions},on:{\"submit\":function($event){return _vm.$emit('submit', $event)},\"cancel\":function($event){return _vm.$emit('request-close')}}})],1)],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/weka_simple_multi_lang/src/components/modal/EditSimpleMultiLangModal.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

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

/***/ "editor_weka/WekaValue":
/*!*********************************************************!*\
  !*** external "tui.require(\"editor_weka/WekaValue\")" ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"editor_weka/WekaValue\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22editor_weka/WekaValue\\%22)%22?");

/***/ }),

/***/ "editor_weka/components/Weka":
/*!***************************************************************!*\
  !*** external "tui.require(\"editor_weka/components/Weka\")" ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"editor_weka/components/Weka\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22editor_weka/components/Weka\\%22)%22?");

/***/ }),

/***/ "editor_weka/components/nodes/BaseNode":
/*!*************************************************************************!*\
  !*** external "tui.require(\"editor_weka/components/nodes/BaseNode\")" ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"editor_weka/components/nodes/BaseNode\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22editor_weka/components/nodes/BaseNode\\%22)%22?");

/***/ }),

/***/ "editor_weka/extensions/Base":
/*!***************************************************************!*\
  !*** external "tui.require(\"editor_weka/extensions/Base\")" ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"editor_weka/extensions/Base\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22editor_weka/extensions/Base\\%22)%22?");

/***/ }),

/***/ "editor_weka/extensions/util":
/*!***************************************************************!*\
  !*** external "tui.require(\"editor_weka/extensions/util\")" ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"editor_weka/extensions/util\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22editor_weka/extensions/util\\%22)%22?");

/***/ }),

/***/ "editor_weka/toolbar":
/*!*******************************************************!*\
  !*** external "tui.require(\"editor_weka/toolbar\")" ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"editor_weka/toolbar\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22editor_weka/toolbar\\%22)%22?");

/***/ }),

/***/ "ext_prosemirror/state":
/*!*********************************************************!*\
  !*** external "tui.require(\"ext_prosemirror/state\")" ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"ext_prosemirror/state\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22ext_prosemirror/state\\%22)%22?");

/***/ }),

/***/ "ext_prosemirror/view":
/*!********************************************************!*\
  !*** external "tui.require(\"ext_prosemirror/view\")" ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"ext_prosemirror/view\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22ext_prosemirror/view\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/Button":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/Button\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/Button\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/Button\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/ButtonGroup":
/*!**********************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/ButtonGroup\")" ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/ButtonGroup\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/ButtonGroup\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/ButtonIcon":
/*!*********************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/ButtonIcon\")" ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/ButtonIcon\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/ButtonIcon\\%22)%22?");

/***/ }),

/***/ "tui/components/buttons/Cancel":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/buttons/Cancel\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/buttons/Cancel\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/buttons/Cancel\\%22)%22?");

/***/ }),

/***/ "tui/components/form/FormRow":
/*!***************************************************************!*\
  !*** external "tui.require(\"tui/components/form/FormRow\")" ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/form/FormRow\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/form/FormRow\\%22)%22?");

/***/ }),

/***/ "tui/components/icons/Add":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/icons/Add\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/icons/Add\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/icons/Add\\%22)%22?");

/***/ }),

/***/ "tui/components/icons/Delete":
/*!***************************************************************!*\
  !*** external "tui.require(\"tui/components/icons/Delete\")" ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/icons/Delete\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/icons/Delete\\%22)%22?");

/***/ }),

/***/ "tui/components/icons/Edit":
/*!*************************************************************!*\
  !*** external "tui.require(\"tui/components/icons/Edit\")" ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/icons/Edit\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/icons/Edit\\%22)%22?");

/***/ }),

/***/ "tui/components/icons/MultiLang":
/*!******************************************************************!*\
  !*** external "tui.require(\"tui/components/icons/MultiLang\")" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/icons/MultiLang\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/icons/MultiLang\\%22)%22?");

/***/ }),

/***/ "tui/components/modal/Modal":
/*!**************************************************************!*\
  !*** external "tui.require(\"tui/components/modal/Modal\")" ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/modal/Modal\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/modal/Modal\\%22)%22?");

/***/ }),

/***/ "tui/components/modal/ModalContent":
/*!*********************************************************************!*\
  !*** external "tui.require(\"tui/components/modal/ModalContent\")" ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/modal/ModalContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/modal/ModalContent\\%22)%22?");

/***/ }),

/***/ "tui/components/modal/ModalPresenter":
/*!***********************************************************************!*\
  !*** external "tui.require(\"tui/components/modal/ModalPresenter\")" ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/modal/ModalPresenter\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/modal/ModalPresenter\\%22)%22?");

/***/ }),

/***/ "tui/components/uniform/FormField":
/*!********************************************************************!*\
  !*** external "tui.require(\"tui/components/uniform/FormField\")" ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/uniform/FormField\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/uniform/FormField\\%22)%22?");

/***/ }),

/***/ "tui/components/uniform/FormText":
/*!*******************************************************************!*\
  !*** external "tui.require(\"tui/components/uniform/FormText\")" ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/uniform/FormText\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/uniform/FormText\\%22)%22?");

/***/ }),

/***/ "tui/components/uniform/Uniform":
/*!******************************************************************!*\
  !*** external "tui.require(\"tui/components/uniform/Uniform\")" ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/uniform/Uniform\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/uniform/Uniform\\%22)%22?");

/***/ }),

/***/ "tui/i18n":
/*!********************************************!*\
  !*** external "tui.require(\"tui/i18n\")" ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/i18n\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/i18n\\%22)%22?");

/***/ }),

/***/ "tui/util":
/*!********************************************!*\
  !*** external "tui.require(\"tui/util\")" ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/util\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/util\\%22)%22?");

/***/ }),

/***/ "weka_simple_multi_lang/components/MultiLangBlock":
/*!************************************************************************************!*\
  !*** external "tui.require(\"weka_simple_multi_lang/components/MultiLangBlock\")" ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"weka_simple_multi_lang/components/MultiLangBlock\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22weka_simple_multi_lang/components/MultiLangBlock\\%22)%22?");

/***/ }),

/***/ "weka_simple_multi_lang/components/MultiLangBlockCollection":
/*!**********************************************************************************************!*\
  !*** external "tui.require(\"weka_simple_multi_lang/components/MultiLangBlockCollection\")" ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"weka_simple_multi_lang/components/MultiLangBlockCollection\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22weka_simple_multi_lang/components/MultiLangBlockCollection\\%22)%22?");

/***/ }),

/***/ "weka_simple_multi_lang/components/form/SimpleMultiLangForm":
/*!**********************************************************************************************!*\
  !*** external "tui.require(\"weka_simple_multi_lang/components/form/SimpleMultiLangForm\")" ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"weka_simple_multi_lang/components/form/SimpleMultiLangForm\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22weka_simple_multi_lang/components/form/SimpleMultiLangForm\\%22)%22?");

/***/ }),

/***/ "weka_simple_multi_lang/components/modal/EditSimpleMultiLangModal":
/*!****************************************************************************************************!*\
  !*** external "tui.require(\"weka_simple_multi_lang/components/modal/EditSimpleMultiLangModal\")" ***!
  \****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"weka_simple_multi_lang/components/modal/EditSimpleMultiLangModal\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22weka_simple_multi_lang/components/modal/EditSimpleMultiLangModal\\%22)%22?");

/***/ })

/******/ });