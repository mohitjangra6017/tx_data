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
/******/ 	return __webpack_require__(__webpack_require__.s = "./client/component/performelement_redisplay/src/tui.json");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./client/component/performelement_redisplay/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\]internal[/\\\\]).)*$":
/*!*****************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components sync ^(?:(?!__[a-z]*__|[/\\]internal[/\\]).)*$ ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var map = {\n\t\"./RedisplayAdminEdit\": \"./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue\",\n\t\"./RedisplayAdminEdit.vue\": \"./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue\",\n\t\"./RedisplayAdminSummary\": \"./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue\",\n\t\"./RedisplayAdminSummary.vue\": \"./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue\",\n\t\"./RedisplayAdminView\": \"./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue\",\n\t\"./RedisplayAdminView.vue\": \"./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue\",\n\t\"./RedisplayParticipantForm\": \"./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue\",\n\t\"./RedisplayParticipantForm.vue\": \"./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue\"\n};\n\n\nfunction webpackContext(req) {\n\tvar id = webpackContextResolve(req);\n\treturn __webpack_require__(id);\n}\nfunction webpackContextResolve(req) {\n\tif(!__webpack_require__.o(map, req)) {\n\t\tvar e = new Error(\"Cannot find module '\" + req + \"'\");\n\t\te.code = 'MODULE_NOT_FOUND';\n\t\tthrow e;\n\t}\n\treturn map[req];\n}\nwebpackContext.keys = function webpackContextKeys() {\n\treturn Object.keys(map);\n};\nwebpackContext.resolve = webpackContextResolve;\nmodule.exports = webpackContext;\nwebpackContext.id = \"./client/component/performelement_redisplay/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\";\n\n//# sourceURL=webpack:///__%5Ba-z%5D*__%7C%5B/\\\\%5Dinternal%5B/\\\\%5D).)*$?./client/component/performelement_redisplay/src/components_sync_^(?:(?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue":
/*!*****************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _RedisplayAdminEdit_vue_vue_type_template_id_75746aac___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RedisplayAdminEdit.vue?vue&type=template&id=75746aac& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=template&id=75746aac&\");\n/* harmony import */ var _RedisplayAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RedisplayAdminEdit.vue?vue&type=script&lang=js& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _RedisplayAdminEdit_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./RedisplayAdminEdit.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _RedisplayAdminEdit_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_RedisplayAdminEdit_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _RedisplayAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _RedisplayAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _RedisplayAdminEdit_vue_vue_type_template_id_75746aac___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _RedisplayAdminEdit_vue_vue_type_template_id_75746aac___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _RedisplayAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_RedisplayAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!****************************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \****************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminEdit_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./RedisplayAdminEdit.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayAdminEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=template&id=75746aac&":
/*!************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=template&id=75746aac& ***!
  \************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminEdit_vue_vue_type_template_id_75746aac___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayAdminEdit.vue?vue&type=template&id=75746aac& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=template&id=75746aac&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminEdit_vue_vue_type_template_id_75746aac___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminEdit_vue_vue_type_template_id_75746aac___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue":
/*!********************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _RedisplayAdminSummary_vue_vue_type_template_id_9dfe3818___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818&\");\n/* harmony import */ var _RedisplayAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RedisplayAdminSummary.vue?vue&type=script&lang=js& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _RedisplayAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _RedisplayAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _RedisplayAdminSummary_vue_vue_type_template_id_9dfe3818___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _RedisplayAdminSummary_vue_vue_type_template_id_9dfe3818___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _RedisplayAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"] === 'function') Object(_RedisplayAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*******************************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*******************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminSummary_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./RedisplayAdminSummary.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayAdminSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818&":
/*!***************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818& ***!
  \***************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminSummary_vue_vue_type_template_id_9dfe3818___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminSummary_vue_vue_type_template_id_9dfe3818___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminSummary_vue_vue_type_template_id_9dfe3818___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue":
/*!*****************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _RedisplayAdminView_vue_vue_type_template_id_5d3e71c7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RedisplayAdminView.vue?vue&type=template&id=5d3e71c7& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=template&id=5d3e71c7&\");\n/* harmony import */ var _RedisplayAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RedisplayAdminView.vue?vue&type=script&lang=js& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _RedisplayAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./RedisplayAdminView.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _RedisplayAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_RedisplayAdminView_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n/* harmony import */ var _RedisplayAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _RedisplayAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _RedisplayAdminView_vue_vue_type_template_id_5d3e71c7___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _RedisplayAdminView_vue_vue_type_template_id_5d3e71c7___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* custom blocks */\n\nif (typeof _RedisplayAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"] === 'function') Object(_RedisplayAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(component)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_redisplay/src/components/RedisplayAdminView.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!****************************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \****************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../tooling/webpack/tui_lang_strings_loader.js??ref--7-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings */ \"./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_tooling_webpack_tui_lang_strings_loader_js_ref_7_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminView_vue_vue_type_custom_index_0_blockType_lang_strings__WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./RedisplayAdminView.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayAdminView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=style&index=0&lang=scss&":
/*!***************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=style&index=0&lang=scss& ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=template&id=5d3e71c7&":
/*!************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=template&id=5d3e71c7& ***!
  \************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminView_vue_vue_type_template_id_5d3e71c7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayAdminView.vue?vue&type=template&id=5d3e71c7& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=template&id=5d3e71c7&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminView_vue_vue_type_template_id_5d3e71c7___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayAdminView_vue_vue_type_template_id_5d3e71c7___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue":
/*!***********************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue ***!
  \***********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _RedisplayParticipantForm_vue_vue_type_template_id_aa6f77ec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec& */ \"./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec&\");\n/* harmony import */ var _RedisplayParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RedisplayParticipantForm.vue?vue&type=script&lang=js& */ \"./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _RedisplayParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./RedisplayParticipantForm.vue?vue&type=style&index=0&lang=scss& */ \"./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=style&index=0&lang=scss&\");\n/* harmony import */ var _RedisplayParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_RedisplayParticipantForm_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(\n  _RedisplayParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _RedisplayParticipantForm_vue_vue_type_template_id_aa6f77ec___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _RedisplayParticipantForm_vue_vue_type_template_id_aa6f77ec___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\ncomponent.options.__hasBlocks = {\"script\":true,\"template\":true};\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--1-0!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!../../../../../node_modules/source-map-loader/dist/cjs.js??ref--2-0!./RedisplayParticipantForm.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_1_0_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_node_modules_source_map_loader_dist_cjs_js_ref_2_0_RedisplayParticipantForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); \n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=style&index=0&lang=scss&":
/*!*********************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=style&index=0&lang=scss& ***!
  \*********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec&":
/*!******************************************************************************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec& ***!
  \******************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayParticipantForm_vue_vue_type_template_id_aa6f77ec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../tooling/webpack/tui_vue_loader.js??ref--3-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec& */ \"./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayParticipantForm_vue_vue_type_template_id_aa6f77ec___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_tooling_webpack_tui_vue_loader_js_ref_3_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedisplayParticipantForm_vue_vue_type_template_id_aa6f77ec___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?");

/***/ }),

/***/ "./client/component/performelement_redisplay/src/tui.json":
/*!****************************************************************!*\
  !*** ./client/component/performelement_redisplay/src/tui.json ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function() {\n\"use strict\";\n\nif (typeof tui !== 'undefined' && tui._bundle.isLoaded(\"performelement_redisplay\")) {\n  console.warn(\n    '[tui bundle] The bundle \"' + \"performelement_redisplay\" +\n    '\" is already loaded, skipping initialisation.'\n  );\n  return;\n};\ntui._bundle.register(\"performelement_redisplay\")\ntui._bundle.addModulesFromContext(\"performelement_redisplay/components\", __webpack_require__(\"./client/component/performelement_redisplay/src/components sync recursive ^(?:(?!__[a-z]*__|[/\\\\\\\\]internal[/\\\\\\\\]).)*$\"));\n}();\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/tui.json?");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_redisplay\": [\n    \"activity_name_with_status\",\n    \"current_activity\",\n    \"no_available_questions\",\n    \"select_activity\",\n    \"select_question_element\",\n    \"source_activity_value\",\n    \"source_activity_value_help\",\n    \"source_element_option\",\n    \"source_question_element_value\",\n    \"source_question_element_value_help\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_redisplay\": [\n    \"activity_name_with_status\",\n    \"current_activity\",\n    \"source_activity_value\",\n    \"source_element_option\",\n    \"source_question_element_value\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./client/tooling/webpack/tui_lang_strings_loader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=custom&index=0&blockType=lang-strings ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (component) {\n        component.options.__langStrings = \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n{\n  \"performelement_redisplay\": [\n    \"redisplayed_element_admin_preview\"\n  ]\n}\n;\n    });\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?./client/tooling/webpack/tui_lang_strings_loader.js??ref--7-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/uniform */ \"tui/components/uniform\");\n/* harmony import */ var tui_components_uniform__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! tui/components/loading/Loader */ \"tui/components/loading/Loader\");\n/* harmony import */ var tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! mod_perform/components/element/PerformAdminCustomElementEdit */ \"mod_perform/components/element/PerformAdminCustomElementEdit\");\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var performelement_redisplay_graphql_source_activities__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! performelement_redisplay/graphql/source_activities */ \"./server/mod/perform/element/redisplay/webapi/ajax/source_activities.graphql\");\n/* harmony import */ var performelement_redisplay_graphql_source_activity_question_elements__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! performelement_redisplay/graphql/source_activity_question_elements */ \"./server/mod/perform/element/redisplay/webapi/ajax/source_activity_question_elements.graphql\");\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    FormRow: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FormRow\"],\n    FormSelect: tui_components_uniform__WEBPACK_IMPORTED_MODULE_0__[\"FormSelect\"],\n    Loader: tui_components_loading_Loader__WEBPACK_IMPORTED_MODULE_1___default.a,\n    PerformAdminCustomElementEdit: mod_perform_components_element_PerformAdminCustomElementEdit__WEBPACK_IMPORTED_MODULE_2___default.a\n  },\n  inheritAttrs: false,\n  props: {\n    identifier: String,\n    isRequired: Boolean,\n    rawData: Object,\n    rawTitle: String,\n    settings: Object,\n    data: Object,\n    currentActivityId: Number\n  },\n  data: function data() {\n    return {\n      initialValues: {\n        activityId: this.rawData.activityId || null,\n        sourceSectionElementId: this.rawData.sourceSectionElementId || null,\n        identifier: this.identifier,\n        rawTitle: this.rawTitle,\n        responseRequired: this.isRequired\n      },\n      activities: [],\n      selectedActivityElementOptions: [],\n      selectedActivityId: this.rawData.activityId || null,\n      loadingSectionElements: false\n    };\n  },\n  computed: {\n    /**\n     * Restructure activity data to display options in a select list\n     *\n     */\n    activityOptions: function activityOptions() {\n      var _this = this;\n\n      var defaultOption = {\n        id: null,\n        label: this.$str('select_activity', 'performelement_redisplay')\n      };\n      var options = this.activities.map(function (activity) {\n        return {\n          id: activity.id,\n          label: _this.getActivityStatusLabel(activity)\n        };\n      });\n      options.unshift(defaultOption);\n      return options;\n    }\n  },\n  apollo: {\n    activities: {\n      query: performelement_redisplay_graphql_source_activities__WEBPACK_IMPORTED_MODULE_3__[\"default\"],\n      update: function update(data) {\n        return data.mod_perform_activities;\n      }\n    }\n  },\n  mounted: function mounted() {\n    if (!this.rawData.activityId) {\n      return;\n    } // Get section element options if existing selected activity ID\n\n\n    this.fetchSourceSectionElements(this.rawData.activityId);\n  },\n  methods: {\n    /**\n     * Update option values for element ID based on user input for activity ID\n     *\n     * @param {Object} values\n     */\n    updateOptionValues: function updateOptionValues(values) {\n      if (values.activityId === this.selectedActivityId) {\n        return;\n      }\n\n      if (!values || !values.activityId) {\n        this.selectedActivityId = null;\n        this.selectedActivityElementOptions = [];\n        return;\n      }\n\n      this.selectedActivityId = values.activityId;\n      this.resetQuestionElementFormValue();\n      this.fetchSourceSectionElements(values.activityId);\n    },\n\n    /**\n     * Fetch source section element data for selected activity & restructure result data\n     * for compatibility with a select component\n     *\n     * @param {Number} activityId\n     */\n    fetchSourceSectionElements: function fetchSourceSectionElements(activityId) {\n      var _this2 = this;\n\n      this.loadingSectionElements = true;\n      this.$apollo.query({\n        query: performelement_redisplay_graphql_source_activity_question_elements__WEBPACK_IMPORTED_MODULE_4__[\"default\"],\n        variables: {\n          input: {\n            activity_id: activityId\n          }\n        },\n        fetchPolicy: 'network-only'\n      }).then(function (data) {\n        _this2.processSectionElements(data);\n\n        _this2.loadingSectionElements = false;\n      });\n    },\n\n    /**\n     * Process the section elements to select options.\n     *\n     * @param {Object} data\n     */\n    processSectionElements: function processSectionElements(data) {\n      var _this3 = this;\n\n      var groups = data.data.performelement_redisplay_source_activity_question_elements.sections;\n      var elementOptions;\n\n      if (groups.length === 0) {\n        this.selectedActivityElementOptions = [{\n          id: null,\n          label: this.$str('no_available_questions', 'performelement_redisplay')\n        }];\n        return;\n      } // If there are multiple groups\n\n\n      if (groups.length > 1) {\n        elementOptions = groups.map(function (group) {\n          return {\n            label: group.title,\n            options: _this3.filterLinkedReview(group.respondable_section_elements).map(function (sectionElement) {\n              return {\n                id: sectionElement.id,\n                label: _this3.getElementLabel(sectionElement.element)\n              };\n            })\n          };\n        });\n      } else {\n        elementOptions = this.filterLinkedReview(groups[0].respondable_section_elements).map(function (sectionElement) {\n          return {\n            id: sectionElement.id,\n            label: _this3.getElementLabel(sectionElement.element)\n          };\n        });\n      }\n\n      var defaultOption = {\n        id: null,\n        label: this.$str('select_question_element', 'performelement_redisplay')\n      };\n      elementOptions.unshift(defaultOption);\n      this.selectedActivityElementOptions = elementOptions;\n    },\n\n    /**\n     * Filter out linked_review elements. Todo: fix in TL-30351.\n     *\n     * @param {Array} sectionElements\n     * @return {Array}\n     */\n    filterLinkedReview: function filterLinkedReview(sectionElements) {\n      return sectionElements.filter(function (sectionElement) {\n        return sectionElement.element.element_plugin.plugin_name !== 'linked_review';\n      });\n    },\n\n    /**\n     * Get activity status and append to the select option label\n     *\n     * @param {Object} activityItem\n     * @return {String}\n     */\n    getActivityStatusLabel: function getActivityStatusLabel(activityItem) {\n      var activityStatus = activityItem.state_details.display_name;\n\n      if (this.currentActivityId === parseInt(activityItem.id)) {\n        activityStatus = this.$str('current_activity', 'performelement_redisplay');\n      }\n\n      return this.$str('activity_name_with_status', 'performelement_redisplay', {\n        activity_name: activityItem.name,\n        activity_status: activityStatus\n      });\n    },\n\n    /**\n     * Get Element option label\n     *\n     * @param {Object} element\n     * @return {String}\n     */\n    getElementLabel: function getElementLabel(element) {\n      return this.$str('source_element_option', 'performelement_redisplay', {\n        element_title: element.title,\n        element_plugin_name: element.element_plugin.name\n      });\n    },\n\n    /**\n     * Clear the source question element value in the uniform\n     *\n     */\n    resetQuestionElementFormValue: function resetQuestionElementFormValue() {\n      this.$refs.redisplayUniform.update('sourceSectionElementId', null);\n    },\n\n    /**\n     * Save redisplay element.\n     *\n     * @param {Object} event\n     */\n    saveRedisplayElement: function saveRedisplayElement(event) {\n      delete event.data.activityId;\n      this.$emit('update', event);\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! mod_perform/components/element/PerformAdminCustomElementSummary */ \"mod_perform/components/element/PerformAdminCustomElementSummary\");\n/* harmony import */ var mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    PerformAdminCustomElementSummary: mod_perform_components_element_PerformAdminCustomElementSummary__WEBPACK_IMPORTED_MODULE_0___default.a\n  },\n  props: {\n    data: Object,\n    identifier: String,\n    isRequired: Boolean,\n    settings: Object,\n    title: String,\n    type: Object,\n    currentActivityId: Number\n  },\n  data: function data() {\n    return {\n      extraFields: [{\n        title: this.$str('source_activity_value', 'performelement_redisplay'),\n        value: this.$str('activity_name_with_status', 'performelement_redisplay', {\n          activity_name: this.data.activityName,\n          activity_status: this.activityStatus\n        })\n      }, {\n        title: this.$str('source_question_element_value', 'performelement_redisplay'),\n        value: this.$str('source_element_option', 'performelement_redisplay', {\n          element_title: this.data.elementTitle,\n          element_plugin_name: this.data.elementPluginName\n        })\n      }]\n    };\n  },\n  calculated: {\n    activityStatus: function activityStatus() {\n      return parseInt(this.data.activityId) === this.currentActivityId ? this.$str('current_activity', 'performelement_redisplay') : this.data.activityStatus;\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/card/Card */ \"tui/components/card/Card\");\n/* harmony import */ var tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0__);\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Card: tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0___default.a\n  },\n  inheritAttrs: false,\n  props: {\n    data: Object\n  }\n});\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./node_modules/source-map-loader/dist/cjs.js?!./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0!./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tui/components/card/Card */ \"tui/components/card/Card\");\n/* harmony import */ var tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! mod_perform/components/element/ElementParticipantFormContent */ \"mod_perform/components/element/ElementParticipantFormContent\");\n/* harmony import */ var mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var performelement_redisplay_graphql_subject_instance_previous_responses__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! performelement_redisplay/graphql/subject_instance_previous_responses */ \"./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses.graphql\");\n/* harmony import */ var performelement_redisplay_graphql_subject_instance_previous_responses_nosession__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! performelement_redisplay/graphql/subject_instance_previous_responses_nosession */ \"./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses_nosession.graphql\");\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n//\n\n\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  components: {\n    Card: tui_components_card_Card__WEBPACK_IMPORTED_MODULE_0___default.a,\n    ElementParticipantFormContent: mod_perform_components_element_ElementParticipantFormContent__WEBPACK_IMPORTED_MODULE_1___default.a\n  },\n  props: {\n    element: {\n      type: Object,\n      validator: function validator(value) {\n        return value.data && value.data.elementPluginDisplayComponent;\n      }\n    },\n    token: String,\n    subjectInstanceId: Number\n  },\n  data: function data() {\n    return {\n      ready: false,\n      redisplayData: {}\n    };\n  },\n  apollo: {\n    redisplayData: {\n      query: function query() {\n        if (!this.element.participantSectionId) {\n          return performelement_redisplay_graphql_subject_instance_previous_responses__WEBPACK_IMPORTED_MODULE_2__[\"default\"];\n        }\n\n        return this.token && this.token.length > 0 ? performelement_redisplay_graphql_subject_instance_previous_responses_nosession__WEBPACK_IMPORTED_MODULE_3__[\"default\"] : performelement_redisplay_graphql_subject_instance_previous_responses__WEBPACK_IMPORTED_MODULE_2__[\"default\"];\n      },\n      fetchPolicy: 'network-only',\n      variables: function variables() {\n        var input = {\n          subject_instance_id: this.subjectInstanceId,\n          section_element_id: this.element.data.sourceSectionElementId\n        };\n\n        if (this.token) {\n          input.token = this.token;\n        }\n\n        if (this.element.participantSectionId) {\n          input.participant_section_id = this.element.participantSectionId;\n        }\n\n        return {\n          input: input\n        };\n      },\n      update: function update(data) {\n        this.ready = true;\n        return data.redisplayData;\n      }\n    }\n  },\n  computed: {\n    otherData: function otherData() {\n      if (!this.element.data.elementPluginDisplayComponent) {\n        return null;\n      }\n\n      var componentTypes = Object.assign({}, this.element.element_plugin, {\n        participant_response_component: this.element.data.elementPluginDisplayComponent\n      });\n      var elementData = Object.assign({}, this.element, {\n        element_plugin: componentTypes\n      });\n      return {\n        element: elementData,\n        other_responder_groups: this.redisplayData.other_responder_groups,\n        response_data_formatted_lines: this.redisplayData.your_response ? this.redisplayData.your_response.response_data_formatted_lines : []\n      };\n    }\n  }\n});\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?./node_modules/babel-loader/lib??ref--1-0!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./node_modules/source-map-loader/dist/cjs.js??ref--2-0");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=template&id=75746aac&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?vue&type=template&id=75746aac& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-redisplayAdminEdit\"},[_c('PerformAdminCustomElementEdit',{ref:\"redisplayUniform\",attrs:{\"initial-values\":_vm.initialValues,\"settings\":_vm.settings,\"validation-mode\":\"auto\"},on:{\"change\":_vm.updateOptionValues,\"cancel\":function($event){return _vm.$emit('display')},\"update\":_vm.saveRedisplayElement}},[_c('FormRow',{attrs:{\"label\":_vm.$str('source_activity_value', 'performelement_redisplay'),\"helpmsg\":_vm.$str('source_activity_value_help', 'performelement_redisplay'),\"required\":true},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\nvar labelId = ref.labelId;\nreturn [_c('Loader',{staticClass:\"tui-redisplayAdminEdit__loader tui-redisplayAdminEdit__loader--charLength-25\",attrs:{\"loading\":_vm.$apollo.queries.activities.loading}},[_c('FormSelect',{attrs:{\"aria-labelledby\":labelId,\"options\":_vm.activityOptions,\"disabled\":_vm.$apollo.queries.activities.loading,\"name\":\"activityId\",\"char-length\":\"25\",\"validations\":function (v) { return [v.required()]; }}})],1)]}}])}),_vm._v(\" \"),(_vm.selectedActivityId)?_c('FormRow',{attrs:{\"label\":_vm.$str('source_question_element_value', 'performelement_redisplay'),\"helpmsg\":_vm.$str('source_question_element_value_help', 'performelement_redisplay'),\"required\":true},scopedSlots:_vm._u([{key:\"default\",fn:function(ref){\nvar labelId = ref.labelId;\nreturn [_c('Loader',{staticClass:\"tui-redisplayAdminEdit__loader tui-redisplayAdminEdit__loader--charLength-25\",attrs:{\"loading\":_vm.$apollo.loading || _vm.loadingSectionElements}},[(_vm.selectedActivityElementOptions)?_c('FormSelect',{attrs:{\"aria-labelledby\":labelId,\"name\":\"sourceSectionElementId\",\"char-length\":\"25\",\"disabled\":_vm.$apollo.loading,\"options\":_vm.selectedActivityElementOptions,\"validations\":function (v) { return [v.required()]; }}}):_vm._e()],1)]}}],null,false,2691299484)}):_vm._e()],1)],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminEdit.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?vue&type=template&id=9dfe3818& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-redisplayAdminSummary\"},[_c('PerformAdminCustomElementSummary',{attrs:{\"extra-fields\":_vm.extraFields,\"identifier\":_vm.identifier,\"is-required\":_vm.isRequired,\"settings\":_vm.settings,\"title\":_vm.title},on:{\"display\":function($event){return _vm.$emit('display')}}})],1)}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminSummary.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=template&id=5d3e71c7&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?vue&type=template&id=5d3e71c7& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:\"tui-redisplayAdminView\"},[(_vm.data.activityName)?_c('div',[_vm._v(\"\\n    \"+_vm._s(_vm.$str('redisplayed_element_admin_preview', 'performelement_redisplay', {\n        activity_name: _vm.data.activityName,\n      }))+\"\\n  \")]):_vm._e(),_vm._v(\" \"),_c('div',{staticClass:\"tui-redisplayAdminView__cardArea\"},[_c('Card',{staticClass:\"tui-redisplayAdminView__card\"},[(_vm.data.elementTitle)?_c('h4',{staticClass:\"tui-redisplayAdminView__card-title\"},[_vm._v(\"\\n        \"+_vm._s(_vm.data.elementTitle)+\"\\n      \")]):_vm._e(),_vm._v(\" \"),(_vm.data.relationships)?_c('div',{staticClass:\"tui-redisplayAdminView__card-content\"},[_vm._v(\"\\n        \"+_vm._s(_vm.data.relationships)+\"\\n      \")]):_vm._e()])],1)])}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayAdminView.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./client/tooling/webpack/tui_vue_loader.js?!./node_modules/vue-loader/lib/index.js?!./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options!./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?vue&type=template&id=aa6f77ec& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.ready)?_c('div',{staticClass:\"tui-redisplayParticipantForm\"},[_vm._v(\"\\n  \"+_vm._s(_vm.redisplayData.title)+\"\\n\\n  \"),_c('div',{staticClass:\"tui-redisplayParticipantForm__cardArea\"},[_c('Card',{staticClass:\"tui-redisplayParticipantForm__card\"},[_c('h4',{staticClass:\"tui-redisplayParticipantForm__card-title\"},[_vm._v(\"\\n        \"+_vm._s(_vm.element.data.elementTitle)+\"\\n      \")]),_vm._v(\" \"),_c('ElementParticipantFormContent',_vm._b({attrs:{\"active-section-is-closed\":true,\"participant-can-answer\":true,\"element\":_vm.element,\"element-components\":_vm.otherData.element.element_plugin,\"section-element\":_vm.otherData,\"show-other-response\":true}},'ElementParticipantFormContent',_vm.$attrs,false))],1)],1)]):_vm._e()}\nvar staticRenderFns = []\nrender._withStripped = true\n\n\n//# sourceURL=webpack:///./client/component/performelement_redisplay/src/components/RedisplayParticipantForm.vue?./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./client/tooling/webpack/tui_vue_loader.js??ref--3-0!./node_modules/vue-loader/lib??vue-loader-options");

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

/***/ "./server/mod/perform/element/redisplay/webapi/ajax/source_activities.graphql":
/*!************************************************************************************!*\
  !*** ./server/mod/perform/element/redisplay/webapi/ajax/source_activities.graphql ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_source_activities\"},\"variableDefinitions\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"mod_perform_activities\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"query_options\"},\"value\":{\"kind\":\"ObjectValue\",\"fields\":[{\"kind\":\"ObjectField\",\"name\":{\"kind\":\"Name\",\"value\":\"sort_by\"},\"value\":{\"kind\":\"StringValue\",\"value\":\"name\",\"block\":false}}]}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"state_details\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"display_name\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/perform/element/redisplay/webapi/ajax/source_activities.graphql?");

/***/ }),

/***/ "./server/mod/perform/element/redisplay/webapi/ajax/source_activity_question_elements.graphql":
/*!****************************************************************************************************!*\
  !*** ./server/mod/perform/element/redisplay/webapi/ajax/source_activity_question_elements.graphql ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_source_activity_question_elements\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"source_activity_question_elements_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_source_activity_question_elements\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"sections\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"title\"},\"name\":{\"kind\":\"Name\",\"value\":\"display_title\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"respondable_section_elements\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"id\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"element\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"title\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"element_plugin\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"name\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"plugin_name\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/perform/element/redisplay/webapi/ajax/source_activity_question_elements.graphql?");

/***/ }),

/***/ "./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses.graphql":
/*!******************************************************************************************************!*\
  !*** ./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses.graphql ***!
  \******************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_subject_instance_previous_responses\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"subject_instance_previous_responses_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"redisplayData\"},\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_subject_instance_previous_responses\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"title\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"is_anonymous\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"your_response\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data_formatted_lines\"},\"arguments\":[],\"directives\":[]}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"other_responder_groups\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"relationship_name\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"responses\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"participant_instance\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"participant\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"fullname\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"profileimagealt\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"profileimageurlsmall\"},\"arguments\":[],\"directives\":[]}]}}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data_formatted_lines\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses.graphql?");

/***/ }),

/***/ "./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses_nosession.graphql":
/*!****************************************************************************************************************!*\
  !*** ./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses_nosession.graphql ***!
  \****************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n\n    var doc = {\"kind\":\"Document\",\"definitions\":[{\"kind\":\"OperationDefinition\",\"operation\":\"query\",\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_subject_instance_previous_responses_nosession\"},\"variableDefinitions\":[{\"kind\":\"VariableDefinition\",\"variable\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}},\"type\":{\"kind\":\"NonNullType\",\"type\":{\"kind\":\"NamedType\",\"name\":{\"kind\":\"Name\",\"value\":\"subject_instance_previous_responses_input\"}}},\"directives\":[]}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"alias\":{\"kind\":\"Name\",\"value\":\"redisplayData\"},\"name\":{\"kind\":\"Name\",\"value\":\"performelement_redisplay_subject_instance_previous_responses_external_participant\"},\"arguments\":[{\"kind\":\"Argument\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"},\"value\":{\"kind\":\"Variable\",\"name\":{\"kind\":\"Name\",\"value\":\"input\"}}}],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"title\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"is_anonymous\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"your_response\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data_formatted_lines\"},\"arguments\":[],\"directives\":[]}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"other_responder_groups\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"relationship_name\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"responses\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"participant_instance\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"participant\"},\"arguments\":[],\"directives\":[],\"selectionSet\":{\"kind\":\"SelectionSet\",\"selections\":[{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"fullname\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"profileimagealt\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"profileimageurlsmall\"},\"arguments\":[],\"directives\":[]}]}}]}},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data\"},\"arguments\":[],\"directives\":[]},{\"kind\":\"Field\",\"name\":{\"kind\":\"Name\",\"value\":\"response_data_formatted_lines\"},\"arguments\":[],\"directives\":[]}]}}]}}]}}]}}]};\n    /* harmony default export */ __webpack_exports__[\"default\"] = (doc);\n  \n\n//# sourceURL=webpack:///./server/mod/perform/element/redisplay/webapi/ajax/subject_instance_previous_responses_nosession.graphql?");

/***/ }),

/***/ "mod_perform/components/element/ElementParticipantFormContent":
/*!************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/ElementParticipantFormContent\")" ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/ElementParticipantFormContent\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/ElementParticipantFormContent\\%22)%22?");

/***/ }),

/***/ "mod_perform/components/element/PerformAdminCustomElementEdit":
/*!************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/PerformAdminCustomElementEdit\")" ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/PerformAdminCustomElementEdit\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/PerformAdminCustomElementEdit\\%22)%22?");

/***/ }),

/***/ "mod_perform/components/element/PerformAdminCustomElementSummary":
/*!***************************************************************************************************!*\
  !*** external "tui.require(\"mod_perform/components/element/PerformAdminCustomElementSummary\")" ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"mod_perform/components/element/PerformAdminCustomElementSummary\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22mod_perform/components/element/PerformAdminCustomElementSummary\\%22)%22?");

/***/ }),

/***/ "tui/components/card/Card":
/*!************************************************************!*\
  !*** external "tui.require(\"tui/components/card/Card\")" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/card/Card\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/card/Card\\%22)%22?");

/***/ }),

/***/ "tui/components/loading/Loader":
/*!*****************************************************************!*\
  !*** external "tui.require(\"tui/components/loading/Loader\")" ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/loading/Loader\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/loading/Loader\\%22)%22?");

/***/ }),

/***/ "tui/components/uniform":
/*!**********************************************************!*\
  !*** external "tui.require(\"tui/components/uniform\")" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = tui.require(\"tui/components/uniform\");\n\n//# sourceURL=webpack:///external_%22tui.require(\\%22tui/components/uniform\\%22)%22?");

/***/ })

/******/ });